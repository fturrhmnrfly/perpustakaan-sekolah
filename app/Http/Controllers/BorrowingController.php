<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display borrowing list (Admin)
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = Borrowing::with(['user', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%$search%");
                })->orWhereHas('book', function ($bookQuery) use ($search) {
                    $bookQuery->where('judul', 'like', "%$search%");
                });
            });
        }

        $borrowings = $query->latest()->paginate(15);

        return view('admin.borrowings.index', [
            'borrowings' => $borrowings,
            'search' => $request->search ?? '',
        ]);
    }

    /**
     * Student request to borrow a book
     */
    public function borrow(Book $book)
    {
        if (! $book->isAvailable()) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam!');
        }

        return view('siswa.books-borrow', compact('book'));
    }

    /**
     * Store a new borrowing request
     */
    public function store(Request $request, Book $book)
    {
        if (! $book->isAvailable()) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam!');
        }

        $validated = $request->validate([
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:today',
        ]);

        $existingBorrowing = Borrowing::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereIn('status', [
                Borrowing::STATUS_PENDING,
                Borrowing::STATUS_ACTIVE,
                Borrowing::STATUS_RETURN_PENDING,
            ])->first();

        if ($existingBorrowing) {
            return redirect()->back()
                ->with('error', 'Anda sudah memiliki transaksi aktif/pending untuk buku ini.');
        }

        $activeOrPendingCount = Borrowing::where('user_id', auth()->id())
            ->whereIn('status', [
                Borrowing::STATUS_PENDING,
                Borrowing::STATUS_ACTIVE,
                Borrowing::STATUS_RETURN_PENDING,
            ])->count();

        if ($activeOrPendingCount >= 3) {
            return redirect()->back()
                ->with('error', 'Maksimal transaksi aktif/pending adalah 3 buku.');
        }

        Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'tanggal_peminjaman' => now(),
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'status' => Borrowing::STATUS_PENDING,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Permintaan peminjaman dikirim. Menunggu persetujuan admin.');
    }

    /**
     * Admin approve borrow request
     */
    public function approveBorrowing(Borrowing $borrowing)
    {
        $this->authorizeAdmin();

        if ($borrowing->status !== Borrowing::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Permintaan ini tidak bisa disetujui.');
        }

        if (! $borrowing->book->isAvailable()) {
            return redirect()->back()->with('error', 'Stok buku habis. Tidak bisa menyetujui peminjaman.');
        }

        $borrowing->update([
            'status' => Borrowing::STATUS_ACTIVE,
            'tanggal_peminjaman' => now(),
            'keterangan' => null,
        ]);

        $borrowing->book->update([
            'stok_tersedia' => $borrowing->book->stok_tersedia - 1,
        ]);

        return redirect()->route('borrowings.index')
            ->with('success', 'Permintaan peminjaman berhasil disetujui.');
    }

    /**
     * Admin reject borrow request
     */
    public function rejectBorrowing(Request $request, Borrowing $borrowing)
    {
        $this->authorizeAdmin();

        if ($borrowing->status !== Borrowing::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Permintaan ini tidak bisa ditolak.');
        }

        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:500',
        ]);

        $borrowing->update([
            'status' => Borrowing::STATUS_REJECTED,
            'keterangan' => $validated['keterangan'] ?? 'Permintaan ditolak oleh admin.',
        ]);

        return redirect()->route('borrowings.index')
            ->with('success', 'Permintaan peminjaman berhasil ditolak.');
    }

    /**
     * Show return request form
     */
    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        if (! in_array($borrowing->status, [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING], true)) {
            return redirect()->route('dashboard')->with('error', 'Transaksi ini tidak bisa diajukan pengembalian.');
        }

        return view('siswa.books-return', compact('borrowing'));
    }

    /**
     * Student request return (waiting admin approval)
     */
    public function processReturn(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($borrowing->status !== Borrowing::STATUS_ACTIVE) {
            return redirect()->back()->with('error', 'Pengembalian sudah diajukan atau transaksi tidak aktif.');
        }

        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:500',
        ]);

        $borrowing->update([
            'status' => Borrowing::STATUS_RETURN_PENDING,
            'keterangan' => $validated['keterangan'] ?? 'Siswa mengajukan pengembalian.',
        ]);

        return redirect()->route('borrowing.returns')
            ->with('success', 'Permintaan pengembalian dikirim. Menunggu persetujuan admin.');
    }

    /**
     * Admin approve return request
     */
    public function adminProcessReturn(Request $request, Borrowing $borrowing)
    {
        $this->authorizeAdmin();

        if ($borrowing->status !== Borrowing::STATUS_RETURN_PENDING) {
            return redirect()->back()->with('error', 'Pengembalian belum diajukan siswa.');
        }

        $validated = $request->validate([
            'kondisi_kembali' => 'required|in:baik,rusak ringan,rusak berat',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $denda = 0;
        if (now()->toDateString() > $borrowing->tanggal_kembali_rencana) {
            $daysLate = Carbon::now()->diffInDays(Carbon::parse($borrowing->tanggal_kembali_rencana));
            $denda = $daysLate * 5000;
        }

        $borrowing->update([
            'tanggal_kembali_aktual' => now(),
            'kondisi_kembali' => $validated['kondisi_kembali'],
            'keterangan' => $validated['keterangan'] ?? $borrowing->keterangan,
            'denda' => $denda,
            'fine_payment_status' => $denda > 0 ? 'unpaid' : 'paid',
            'status' => Borrowing::STATUS_RETURNED,
        ]);

        $borrowing->book->update([
            'stok_tersedia' => $borrowing->book->stok_tersedia + 1,
        ]);

        if ($validated['kondisi_kembali'] !== 'baik') {
            $borrowing->book->update([
                'kondisi' => $validated['kondisi_kembali'],
            ]);
        }

        $message = 'Pengembalian buku berhasil diproses.';
        if ($denda > 0) {
            $message .= ' Denda: Rp '.number_format($denda, 0, ',', '.');
        }

        return redirect()->route('borrowings.index')
            ->with('success', $message);
    }

    /**
     * Show student's borrowing history
     */
    public function history()
    {
        $userId = auth()->id();

        $borrowings = Borrowing::where('user_id', $userId)
            ->with('book')
            ->latest()
            ->paginate(10);

        $borrowingsCount = Borrowing::where('user_id', $userId)->count();
        $activeBorrowingsCount = Borrowing::where('user_id', $userId)
            ->whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])
            ->count();
        $totalFines = Borrowing::where('user_id', $userId)->sum('denda');

        return view('siswa.borrowing-history', [
            'borrowings' => $borrowings,
            'borrowingsCount' => $borrowingsCount,
            'activeBorrowingsCount' => $activeBorrowingsCount,
            'totalFines' => $totalFines,
        ]);
    }

    /**
     * Print-friendly student history report
     */
    public function printHistory()
    {
        $data = $this->studentHistoryData();

        return view('reports.siswa-history-report', [
            ...$data,
            'autoPrint' => true,
        ]);
    }

    /**
     * Download student history report as PDF
     */
    public function downloadHistoryPdf()
    {
        $data = $this->studentHistoryData();

        $pdf = Pdf::loadView('reports.siswa-history-report', [
            ...$data,
            'autoPrint' => false,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('riwayat-peminjaman-siswa-'.now()->format('Ymd-His').'.pdf');
    }

    /**
     * Show dedicated return page for students
     */
    public function returnsPage()
    {
        $userId = auth()->id();

        $borrowings = Borrowing::where('user_id', $userId)
            ->whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])
            ->with('book')
            ->latest('tanggal_peminjaman')
            ->paginate(8);

        $returnableCount = Borrowing::where('user_id', $userId)
            ->where('status', Borrowing::STATUS_ACTIVE)
            ->count();

        $waitingApprovalCount = Borrowing::where('user_id', $userId)
            ->where('status', Borrowing::STATUS_RETURN_PENDING)
            ->count();

        $recentHistory = Borrowing::where('user_id', $userId)
            ->with('book')
            ->latest()
            ->limit(8)
            ->get();

        return view('siswa.returns', [
            'borrowings' => $borrowings,
            'returnableCount' => $returnableCount,
            'waitingApprovalCount' => $waitingApprovalCount,
            'recentHistory' => $recentHistory,
        ]);
    }

    /**
     * Show QRIS fine payment page
     */
    public function showFinePayment(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        if ($borrowing->denda <= 0 || $borrowing->status !== Borrowing::STATUS_RETURNED) {
            return redirect()->route('borrowing.history')->with('error', 'Transaksi ini tidak memiliki denda yang harus dibayar.');
        }

        return view('siswa.fine-payment', [
            'borrowing' => $borrowing,
        ]);
    }

    /**
     * Student confirms fine payment via QRIS
     */
    public function processFinePayment(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        if ($borrowing->denda <= 0 || $borrowing->status !== Borrowing::STATUS_RETURNED) {
            return redirect()->route('borrowing.history')->with('error', 'Transaksi ini tidak memiliki denda yang harus dibayar.');
        }

        if ($borrowing->fine_payment_status === 'paid') {
            return redirect()->route('borrowing.history')->with('success', 'Denda transaksi ini sudah dibayar.');
        }

        $validated = $request->validate([
            'fine_payment_note' => 'nullable|string|max:255',
        ]);

        $borrowing->update([
            'fine_payment_status' => 'paid',
            'fine_payment_method' => 'QRIS',
            'fine_payment_note' => $validated['fine_payment_note'] ?? null,
            'fine_paid_at' => now(),
        ]);

        return redirect()->route('borrowing.history')
            ->with('success', 'Pembayaran denda via QRIS berhasil dikonfirmasi.');
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
     * Build student history report data.
     */
    private function studentHistoryData(): array
    {
        $user = auth()->user();
        $borrowings = Borrowing::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->get();

        return [
            'user' => $user,
            'borrowings' => $borrowings,
            'generatedAt' => now(),
            'borrowingsCount' => $borrowings->count(),
            'activeBorrowingsCount' => $borrowings->whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])->count(),
            'totalFines' => $borrowings->sum('denda'),
        ];
    }
}
