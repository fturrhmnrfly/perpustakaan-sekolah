<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Borrowing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        [$hasUnreturnedBooks, $hasUnpaidFines] = $this->checkOutstandingLiabilities($user);

        if ($hasUnreturnedBooks || $hasUnpaidFines) {
            $reasons = [];
            if ($hasUnreturnedBooks) {
                $reasons[] = 'buku belum dikembalikan';
            }
            if ($hasUnpaidFines) {
                $reasons[] = 'denda belum dibayar';
            }

            $reasonText = 'Akun diblokir otomatis karena '.implode(' dan ', $reasons).'.';

            $user->update([
                'blocked_at' => now(),
                'blocked_reason' => $reasonText,
            ]);

            DB::table('sessions')->where('user_id', $user->id)->delete();

            return redirect()->back()
                ->with('error', 'Akun tidak dihapus. '.$reasonText.' Siswa tidak bisa login sampai tanggungan diselesaikan.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }

    /**
     * Unblock student account if all liabilities are resolved
     */
    public function unblock(User $user)
    {
        $this->authorizeAdmin();

        [$hasUnreturnedBooks, $hasUnpaidFines] = $this->checkOutstandingLiabilities($user);
        if ($hasUnreturnedBooks || $hasUnpaidFines) {
            return redirect()->back()
                ->with('error', 'Akun belum bisa dibuka. Masih ada buku belum kembali atau denda belum dibayar.');
        }

        $user->update([
            'blocked_at' => null,
            'blocked_reason' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Akun siswa berhasil dibuka kembali dan bisa login.');
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

    /**
     * Check outstanding liabilities for a user
     *
     * @return array{bool, bool}
     */
    private function checkOutstandingLiabilities(User $user): array
    {
        $hasUnreturnedBooks = $user->borrowings()
            ->whereIn('status', [
                Borrowing::STATUS_PENDING,
                Borrowing::STATUS_ACTIVE,
                Borrowing::STATUS_RETURN_PENDING,
            ])
            ->exists();

        $hasUnpaidFines = $user->borrowings()
            ->where('denda', '>', 0)
            ->where('fine_payment_status', 'unpaid')
            ->exists();

        return [$hasUnreturnedBooks, $hasUnpaidFines];
    }
}
