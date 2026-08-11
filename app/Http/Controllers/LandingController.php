<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user()->isDoctor()) {
                return redirect()->route('doctor.dashboard');
            }
        }

        $specializations = Specialization::where('is_active', true)->get();
        $doctors = Doctor::with(['user', 'specialization'])
            ->where('is_available', true)
            ->where('is_verified', true)
            ->orderByDesc('rating')
            ->take(8)
            ->get();
        $articles = Article::with('author')
            ->published()
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('landing.index', compact('specializations', 'doctors', 'articles'));
    }
}
