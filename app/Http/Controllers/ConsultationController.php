<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isPatient()) {
            $consultations = Consultation::with(['doctor.user', 'doctor.specialization'])
                ->where('patient_id', $user->id)
                ->orderByDesc('created_at')
                ->paginate(10);
        } elseif ($user->isDoctor()) {
            $doctor = $user->doctor ?: \App\Models\Doctor::where('user_id', $user->id)->first();
            $doctorId = $doctor ? $doctor->id : 0;
            $consultations = Consultation::with(['patient'])
                ->where('doctor_id', $doctorId)
                ->orderByDesc('created_at')
                ->paginate(10);
        } else {
            $consultations = Consultation::with(['patient', 'doctor.user'])
                ->orderByDesc('created_at')
                ->paginate(10);
        }

        return view('consultations.index', compact('consultations'));
    }

    public function show($consultationId)
    {
        $consultation = Consultation::with(['patient', 'doctor.user', 'doctor.specialization', 'messages.sender'])
            ->find($consultationId);

        if (!$consultation) {
            return redirect()->route('consultations.index')->with('error', 'Sesi konsultasi tidak ditemukan atau telah dibatalkan.');
        }

        $this->authorizeConsultation($consultation);

        $currentUserId = Auth::id();
        $initialMessages = $consultation->messages->map(function ($msg) use ($currentUserId) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->name,
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('H:i'),
                'is_sent' => $msg->sender_id === $currentUserId,
            ];
        });

        return view('consultations.show', compact('consultation', 'initialMessages'));
    }

    public function sendMessage(Request $request, Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);
        $request->validate(['message' => 'required|string|max:2000']);

        if (!in_array($consultation->status, ['confirmed', 'active'])) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Konsultasi ini tidak aktif.'], 422);
            }
            return back()->with('error', 'Konsultasi ini tidak aktif.');
        }

        if (!$consultation->is_started) {
            $startTime = substr($consultation->consultation_time, 0, 5);
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => "Sesi belum dimulai. Sesi dijadwalkan pukul {$startTime} WIB."], 422);
            }
            return back()->with('error', "Sesi belum dimulai. Sesi dijadwalkan pukul {$startTime} WIB.");
        }

        if ($consultation->is_expired) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Waktu sesi konsultasi telah berakhir.'], 422);
            }
            return back()->with('error', 'Waktu sesi konsultasi telah berakhir.');
        }

        if ($consultation->status === 'confirmed') {
            $consultation->update(['status' => 'active']);
        }

        $message = ConsultationMessage::create([
            'consultation_id' => $consultation->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'type' => 'text',
        ]);

        // Broadcast event for real-time (Reverb)
        // broadcast(new NewConsultationMessage($message))->toOthers();

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            $message->load('sender');
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'message' => $message->message,
                    'created_at' => $message->created_at->format('H:i'),
                    'is_sent' => true,
                ]
            ]);
        }

        return back();
    }

    public function complete(Consultation $consultation, Request $request)
    {
        $user = Auth::user();
        if (!$user->isDoctor() || !$user->doctor || $user->doctor->id !== $consultation->doctor_id) {
            abort(403);
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $consultation->update([
            'status' => 'completed',
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Konsultasi berhasil diselesaikan.');
    }

    public function confirm(Consultation $consultation)
    {
        $user = Auth::user();
        $doctor = $user->doctor ?: \App\Models\Doctor::where('user_id', $user->id)->first();
        if (!$user->isDoctor() || !$doctor || $doctor->id !== $consultation->doctor_id) {
            abort(403);
        }
        $consultation->status = 'confirmed';
        $consultation->updated_at = \Carbon\Carbon::now();
        $consultation->save();

        return back()->with('success', 'Konsultasi berhasil disetujui & dikonfirmasi! Ruang chat medis telah terbuka.');
    }

    public function cancel(Consultation $consultation)
    {
        $user = Auth::user();
        if ($user->id !== $consultation->patient_id && !$user->isAdmin() && !($user->isDoctor() && $user->doctor?->id === $consultation->doctor_id)) {
            abort(403);
        }
        $consultation->update(['status' => 'cancelled']);
        return back()->with('success', 'Konsultasi berhasil dibatalkan.');
    }

    public function messages(Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);
        $currentUserId = Auth::id();
        $messages = $consultation->messages()->with('sender')->get()->map(function ($msg) use ($currentUserId) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->name,
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('H:i'),
                'is_sent' => $msg->sender_id === $currentUserId,
            ];
        });

        return response()->json([
            'status' => $consultation->status,
            'diagnosis' => $consultation->diagnosis,
            'prescription' => $consultation->prescription,
            'notes' => $consultation->notes,
            'messages' => $messages,
        ]);
    }

    private function authorizeConsultation(Consultation $consultation): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) return;
        if ($user->isPatient() && $user->id !== $consultation->patient_id) abort(403);
        if ($user->isDoctor()) {
            $doctor = $user->doctor ?: \App\Models\Doctor::where('user_id', $user->id)->first();
            if (!$doctor || $doctor->id !== $consultation->doctor_id) {
                abort(403);
            }
        }
    }
}
