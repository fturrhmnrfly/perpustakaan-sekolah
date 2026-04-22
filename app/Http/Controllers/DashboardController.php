<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on user role
     */
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    /**
     * Admin dashboard
     */
    private function adminDashboard()
    {
        $totalBuku = Book::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $peminjaman_aktif = Borrowing::whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])->count();
        $permintaan_masuk = Borrowing::whereIn('status', [Borrowing::STATUS_PENDING, Borrowing::STATUS_RETURN_PENDING])->count();
        $peminjaman_terlambat = Borrowing::whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])
            ->where('tanggal_kembali_rencana', '<', now()->toDateString())
            ->count();

        $recentBorrowings = Borrowing::with(['user', 'book'])
            ->latest('tanggal_peminjaman')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'totalBuku' => $totalBuku,
            'totalSiswa' => $totalSiswa,
            'peminjaman_aktif' => $peminjaman_aktif,
            'permintaan_masuk' => $permintaan_masuk,
            'peminjaman_terlambat' => $peminjaman_terlambat,
            'recentBorrowings' => $recentBorrowings,
        ]);
    }

    /**
     * Student dashboard
     */
    private function studentDashboard()
    {
        $user = auth()->user();

        $activeBorrowings = Borrowing::where('user_id', $user->id)
            ->whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])
            ->with('book')
            ->get();

        $pendingBorrowingsCount = Borrowing::where('user_id', $user->id)
            ->where('status', Borrowing::STATUS_PENDING)
            ->count();

        $recentBorrowings = Borrowing::where('user_id', $user->id)
            ->whereIn('status', [Borrowing::STATUS_RETURNED, Borrowing::STATUS_REJECTED, Borrowing::STATUS_LOST])
            ->with('book')
            ->latest()
            ->limit(5)
            ->get();

        $activeBorrowingsCount = $activeBorrowings->count();
        $totalFines = Borrowing::where('user_id', $user->id)->sum('denda');
        $availableBooksCount = Book::where('stok_tersedia', '>', 0)->count();

        return view('siswa.dashboard', [
            'activeBorrowings' => $activeBorrowings,
            'recentBorrowings' => $recentBorrowings,
            'activeBorrowingsCount' => $activeBorrowingsCount,
            'pendingBorrowingsCount' => $pendingBorrowingsCount,
            'totalFines' => $totalFines,
            'availableBooksCount' => $availableBooksCount,
        ]);
    }
}
