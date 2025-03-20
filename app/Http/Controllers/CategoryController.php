<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Categories::create(['name' => $request->category_name]);

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function destroy($id)
    {
        $category = Categories::findOrFail($id);

        // if ($category->books()->count() > 0) {
        //     return response()->json(['message' => 'category is in use']);
        // }

        $category->delete();
        return response()->json(['message' => 'Category has been deleted']);
    }
}
