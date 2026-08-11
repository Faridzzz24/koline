<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::orderBy('name')->paginate(20);
        return view('admin.medicines.index', compact('medicines'));
    }
    public function create() { return view('admin.medicines.create'); }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', 'category' => 'required',
            'price' => 'required|numeric', 'stock' => 'required|integer',
            'description' => 'required',
        ]);
        Medicine::create(array_merge($request->all(), ['slug' => Str::slug($request->name), 'is_active' => true]));
        return redirect()->route('admin.apotek.index')->with('success', 'Produk ditambahkan.');
    }
    public function edit(Medicine $apotek) { return view('admin.medicines.edit', compact('apotek')); }
    public function update(Request $request, Medicine $apotek)
    {
        $apotek->update($request->all());
        return redirect()->route('admin.apotek.index')->with('success', 'Produk diperbarui.');
    }
    public function destroy(Medicine $apotek)
    {
        $apotek->delete();
        return redirect()->route('admin.apotek.index')->with('success', 'Produk dihapus.');
    }
    public function show(Medicine $apotek) { return view('admin.medicines.show', compact('apotek')); }
}
