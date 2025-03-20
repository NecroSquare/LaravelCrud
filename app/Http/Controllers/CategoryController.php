<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);
        
        $exists = Categories::whereRaw('LOWER(name) = ?', [strtolower($request->category_name)])->exists();
        
        if ($exists) {
            return redirect()->back()->with('error', 'Kategori sudah ada!');
        }
        
        Categories::create([
            'name' => $request->category_name,
        ]);
        
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }
    
        public function destroy(Request $request)
    {
        $category = Categories::findOrFail($request->category_id);

        if ($category->books()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori masih memiliki buku.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }


}
