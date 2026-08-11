<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'patient')->count(),
            'total_doctors' => Doctor::count(),
            'total_consultations' => Consultation::count(),
            'total_orders' => Order::count(),
            'active_consultations' => Consultation::whereIn('status', ['pending', 'confirmed', 'active'])->count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
        ];

        $recentConsultations = Consultation::with(['patient', 'doctor.user'])
            ->orderByDesc('created_at')->take(5)->get();
        $recentOrders = Order::with('user')->orderByDesc('created_at')->take(5)->get();

        // Monthly consultations for chart (last 6 months)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartData[] = [
                'label' => $month->translatedFormat('M Y'),
                'count' => Consultation::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentConsultations', 'recentOrders', 'chartData'));
    }
}
