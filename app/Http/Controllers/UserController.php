<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin)
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = User::where('role', 'siswa');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('no_identitas', 'like', "%$search%");
            });
        }

        $users = $query->paginate(15);

        return view('admin.users.index', [
            'users' => $users,
            'search' => $request->search ?? '',
        ]);
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'no_identitas' => 'required|string|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'siswa';

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        $this->authorizeAdmin();

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,'.$user->id,
            'no_identitas' => 'required|string|unique:users,no_identitas,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Siswa berhasil diperbarui!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if ($user->borrowings()->whereIn('status', ['menunggu_persetujuan', 'aktif', 'menunggu_pengembalian'])->exists()) {
            return redirect()->back()
                ->with('error', 'Siswa tidak bisa dihapus karena masih memiliki transaksi aktif/pending!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }

    /**
     * Show user's borrowing history (Admin)
     */
    public function showBorrowingHistory(User $user)
    {
        $this->authorizeAdmin();

        $borrowings = $user->borrowings()
            ->with('book')
            ->orderByDesc('tanggal_peminjaman')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.users.borrowing-history', [
            'user' => $user,
            'borrowings' => $borrowings,
        ]);
    }

    /**
     * Print-friendly borrowing history report for selected student (Admin)
     */
    public function printBorrowingHistory(User $user)
    {
        $this->authorizeAdmin();

        $borrowings = $user->borrowings()
            ->with('book')
            ->orderByDesc('tanggal_peminjaman')
            ->orderByDesc('id')
            ->get();

        return view('reports.admin-user-history-report', [
            'user' => $user,
            'borrowings' => $borrowings,
            'generatedAt' => now(),
            'autoPrint' => true,
        ]);
    }

    /**
     * Download borrowing history report as PDF for selected student (Admin)
     */
    public function downloadBorrowingHistoryPdf(User $user)
    {
        $this->authorizeAdmin();

        $borrowings = $user->borrowings()
            ->with('book')
            ->orderByDesc('tanggal_peminjaman')
            ->orderByDesc('id')
            ->get();

        $pdf = Pdf::loadView('reports.admin-user-history-report', [
            'user' => $user,
            'borrowings' => $borrowings,
            'generatedAt' => now(),
            'autoPrint' => false,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('riwayat-peminjaman-'.$user->name.'-'.now()->format('Ymd-His').'.pdf');
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
