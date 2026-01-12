<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display all medicines with filtering and search.
     */
    public function index(Request $request)
    {
        $query = Medicine::with('category');

        // Filter by category
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $medicines = $query->paginate(12);
        $categories = Category::all();

        return view('catalog.index', compact('medicines', 'categories'));
    }

    /**
     * Display a single medicine detail.
     */
    public function show(Medicine $medicine)
    {
        $medicine->load('category');
        return view('catalog.show', compact('medicine'));
    }
}
