<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories (Admin)
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = Category::query()->withCount('books');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%");
        }

        $categories = $query->paginate(10);

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $request->search ?? '',
        ]);
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the category
     */
    public function edit(Category $category)
    {
        $this->authorizeAdmin();

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        $this->authorizeAdmin();

        if ($category->books()->exists()) {
            return redirect()->back()
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki buku!');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Authorize admin only
     */
    private function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }
}
