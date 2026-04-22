<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books (Admin)
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = Book::with('category');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('pengarang', 'like', "%$search%")
                    ->orWhere('isbn', 'like', "%$search%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by availability
        if ($request->has('availability')) {
            if ($request->availability == 'available') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->availability == 'unavailable') {
                $query->where('stok_tersedia', '=', 0);
            }
        }

        $books = $query->paginate(10);
        $categories = Category::all();

        return view('admin.books.index', [
            'books' => $books,
            'categories' => $categories,
            'search' => $request->search ?? '',
        ]);
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        $this->authorizeAdmin();
        $categories = Category::all();

        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'category_id' => 'required|exists:categories,id',
            'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
            'stok' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'lokasi' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['stok_tersedia'] = $validated['stok'];
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        return view('admin.books.show', compact('book'));
    }

    /**
     * Show the form for editing the book
     */
    public function edit(Book $book)
    {
        $this->authorizeAdmin();
        $categories = Category::all();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, Book $book)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,'.$book->id,
            'category_id' => 'required|exists:categories,id',
            'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
            'stok' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'lokasi' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Calculate stok_tersedia based on new stok if it changed
        if ($validated['stok'] !== $book->stok) {
            $diff = $validated['stok'] - $book->stok;
            $validated['stok_tersedia'] = max(0, $book->stok_tersedia + $diff);
        }

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Remove the specified book
     */
    public function destroy(Book $book)
    {
        $this->authorizeAdmin();

        if ($book->borrowings()->whereIn('status', ['menunggu_persetujuan', 'aktif', 'menunggu_pengembalian'])->exists()) {
            return redirect()->back()
                ->with('error', 'Buku tidak bisa dihapus karena sedang dipinjam!');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Authorize that the user is admin
     */
    private function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Search books (for student)
     */
    public function search(Request $request)
    {
        $query = Book::with('category')->where('stok_tersedia', '>', 0);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('pengarang', 'like', "%$search%");
            });
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('siswa.books-search', [
            'books' => $books,
            'categories' => $categories,
            'search' => $request->search ?? '',
        ]);
    }
}
