<?php

namespace App\Http\Controllers;

use App\Models\HealthCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthCheckController extends Controller
{
    public function index()
    {
        $history = HealthCheck::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('health-check.index', compact('history'));
    }

    public function bmi()
    {
        return view('health-check.bmi');
    }

    public function storeBmi(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:1|max:500',
            'height' => 'required|numeric|min:50|max:300',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female',
        ]);

        $weight = $request->weight;
        $height = $request->height / 100; // cm to m
        $bmi = round($weight / ($height * $height), 1);

        $category = match(true) {
            $bmi < 18.5 => 'Kurus (Underweight)',
            $bmi < 25.0 => 'Normal',
            $bmi < 30.0 => 'Gemuk (Overweight)',
            default => 'Obesitas',
        };

        $riskLevel = match(true) {
            $bmi < 18.5 || ($bmi >= 25.0 && $bmi < 30.0) => 'moderate',
            $bmi >= 30.0 => 'high',
            default => 'low',
        };

        $idealMin = round(18.5 * $height * $height, 1);
        $idealMax = round(24.9 * $height * $height, 1);

        $inputData = $request->only(['weight', 'height', 'age', 'gender']);
        $resultData = [
            'bmi' => $bmi,
            'category' => $category,
            'ideal_weight_min' => $idealMin,
            'ideal_weight_max' => $idealMax,
        ];

        if (Auth::check()) {
            HealthCheck::create([
                'user_id' => Auth::id(),
                'type' => 'bmi',
                'input_data' => $inputData,
                'result_data' => $resultData,
                'result_summary' => "BMI {$bmi} — {$category}",
                'risk_level' => $riskLevel,
            ]);
        }

        return view('health-check.bmi-result', compact('bmi', 'category', 'riskLevel', 'idealMin', 'idealMax', 'inputData'));
    }

    public function symptomChecker()
    {
        return view('health-check.symptom-checker');
    }

    public function storeSymptom(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'string',
            'duration' => 'required|string',
            'severity' => 'required|in:mild,moderate,severe',
        ]);

        // Simple rule-based symptom analysis
        $symptoms = $request->symptoms;
        $severity = $request->severity;
        $result = $this->analyzeSymptoms($symptoms, $severity);

        $riskLevel = match($severity) {
            'severe' => 'high',
            'moderate' => 'moderate',
            default => 'low',
        };

        if (Auth::check()) {
            HealthCheck::create([
                'user_id' => Auth::id(),
                'type' => 'symptom_checker',
                'input_data' => $request->only(['symptoms', 'duration', 'severity']),
                'result_data' => $result,
                'result_summary' => $result['summary'],
                'risk_level' => $riskLevel,
            ]);
        }

        return view('health-check.symptom-result', compact('symptoms', 'severity', 'result'));
    }

    // ─── 8 Additional KoLine Tools ──────────────────────────────────────────
    public function stresTest()
    {
        return view('health-check.stres');
    }

    public function jantungTest()
    {
        return view('health-check.jantung');
    }

    public function diabetesTest()
    {
        return view('health-check.diabetes');
    }

    public function depresiTest()
    {
        return view('health-check.depresi');
    }

    public function kecemasanTest()
    {
        return view('health-check.kecemasan');
    }

    public function menstruasiTracker()
    {
        return view('health-check.menstruasi');
    }

    public function pengingatObat()
    {
        return view('health-check.pengingat-obat');
    }

    public function kehamilanCalculator()
    {
        return view('health-check.kehamilan');
    }

    public function donasiMedis()
    {
        return view('health-check.donasi');
    }

    private function analyzeSymptoms(array $symptoms, string $severity): array
    {
        $possibleConditions = [];
        $recommendations = [];

        if (in_array('demam', $symptoms) && in_array('batuk', $symptoms)) {
            $possibleConditions[] = 'Infeksi Saluran Pernapasan Atas (ISPA)';
            $possibleConditions[] = 'Influenza (Flu)';
        }
        if (in_array('sakit_kepala', $symptoms) && in_array('mual', $symptoms)) {
            $possibleConditions[] = 'Migrain';
            $possibleConditions[] = 'Hipertensi';
        }
        if (in_array('nyeri_dada', $symptoms)) {
            $possibleConditions[] = 'Perlu evaluasi jantung segera';
            $recommendations[] = 'Segera konsultasikan ke dokter spesialis jantung';
        }
        if (in_array('diare', $symptoms)) {
            $possibleConditions[] = 'Gastroenteritis';
            $possibleConditions[] = 'Keracunan Makanan';
        }

        if (empty($possibleConditions)) {
            $possibleConditions[] = 'Kondisi umum yang perlu dievaluasi dokter';
        }

        if ($severity === 'severe') {
            $recommendations[] = 'Segera kunjungi dokter atau UGD rumah sakit';
        } elseif ($severity === 'moderate') {
            $recommendations[] = 'Konsultasikan ke dokter dalam 1-2 hari ke depan';
        } else {
            $recommendations[] = 'Istirahat yang cukup dan pantau gejala';
            $recommendations[] = 'Minum air yang banyak';
        }

        $recommendations[] = 'Gunakan fitur Konsultasi Online untuk saran lebih lanjut';

        return [
            'possible_conditions' => $possibleConditions,
            'recommendations' => $recommendations,
            'summary' => 'Kemungkinan: ' . implode(', ', array_slice($possibleConditions, 0, 2)),
        ];
    }
}
