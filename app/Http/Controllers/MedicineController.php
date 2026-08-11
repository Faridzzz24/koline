<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('prescription')) {
            $query->where('requires_prescription', $request->prescription === 'yes');
        }

        $medicines = $query->orderBy('name')->get();
        $categories = ['obat_bebas', 'obat_keras', 'suplemen', 'vitamin', 'herbal', 'alat_kesehatan'];

        return view('medicines.index', compact('medicines', 'categories'));
    }

    public function show(Medicine $medicine)
    {
        $related = Medicine::where('category', $medicine->category)
            ->where('id', '!=', $medicine->id)
            ->where('is_active', true)
            ->take(4)
            ->get();
        return view('medicines.show', compact('medicine', 'related'));
    }
}
