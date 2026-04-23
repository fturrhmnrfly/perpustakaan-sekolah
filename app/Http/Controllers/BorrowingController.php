<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Notifications\BorrowingActionNotification;
use App\Notifications\BorrowingFineInvoiceEmailNotification;
use App\Notifications\BorrowingFinePaidEmailNotification;
use App\Notifications\BorrowingReturnApprovedEmailNotification;
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

        $borrowings = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);

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

        $newBorrowing = Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'tanggal_peminjaman' => now(),
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'status' => Borrowing::STATUS_PENDING,
        ]);

        $this->notifyBorrowingAction(
            $newBorrowing->loadMissing('book'),
            'Permintaan peminjaman dikirim',
            'Permintaan peminjaman untuk buku "'.$newBorrowing->book->judul.'" sedang menunggu persetujuan admin.'
        );
        $this->notifyAdmins(
            $newBorrowing,
            'Permintaan peminjaman baru',
            'Siswa '.$newBorrowing->user->name.' mengajukan peminjaman buku "'.$newBorrowing->book->judul.'".',
            route('borrowings.index')
        );

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

        $this->notifyBorrowingAction(
            $borrowing->loadMissing('book'),
            'Peminjaman disetujui',
            'Peminjaman buku "'.$borrowing->book->judul.'" telah disetujui admin. Silakan ambil buku sesuai prosedur.'
        );

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

        $this->notifyBorrowingAction(
            $borrowing->loadMissing('book'),
            'Peminjaman ditolak',
            'Permintaan peminjaman buku "'.$borrowing->book->judul.'" ditolak admin. Cek detail keterangan pada riwayat.'
        );

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

        $this->notifyBorrowingAction(
            $borrowing->loadMissing('book'),
            'Pengembalian diajukan',
            'Permintaan pengembalian buku "'.$borrowing->book->judul.'" berhasil diajukan dan menunggu persetujuan admin.'
        );
        $this->notifyAdmins(
            $borrowing,
            'Permintaan pengembalian baru',
            'Siswa '.$borrowing->user->name.' mengajukan pengembalian buku "'.$borrowing->book->judul.'".',
            route('borrowings.index')
        );

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
            'kondisi_kembali' => 'required|in:baik,rusak ringan,rusak berat,hilang',
            'keterangan' => 'required_unless:kondisi_kembali,baik|nullable|string|max:500',
            'denda_kerusakan' => 'nullable|numeric|min:0',
        ]);

        $dendaKeterlambatan = 0;
        if (now()->toDateString() > $borrowing->tanggal_kembali_rencana) {
            $daysLate = Carbon::now()->diffInDays(Carbon::parse($borrowing->tanggal_kembali_rencana));
            $dendaKeterlambatan = $daysLate * 5000;
        }

        $dendaKerusakan = 0;
        if ($validated['kondisi_kembali'] !== 'baik') {
            $request->validate([
                'denda_kerusakan' => 'required|numeric|min:0',
            ], [
                'denda_kerusakan.required' => 'Nominal denda kerusakan/kehilangan wajib diisi.',
            ]);

            $dendaKerusakan = (float) $validated['denda_kerusakan'];
        }

        $totalDenda = $dendaKeterlambatan + $dendaKerusakan;
        $isLost = $validated['kondisi_kembali'] === Borrowing::STATUS_LOST;

        $nextStatus = Borrowing::STATUS_RETURNED;
        if ($isLost) {
            $nextStatus = Borrowing::STATUS_LOST;
        } elseif ($totalDenda > 0) {
            $nextStatus = Borrowing::STATUS_WAITING_PAYMENT;
        }

        $borrowing->update([
            'tanggal_kembali_aktual' => now(),
            'kondisi_kembali' => $validated['kondisi_kembali'],
            'keterangan' => $validated['keterangan'] ?? $borrowing->keterangan,
            'denda_keterlambatan' => $dendaKeterlambatan,
            'denda_kerusakan' => $dendaKerusakan,
            'denda' => $totalDenda,
            'fine_payment_status' => $totalDenda > 0 ? 'unpaid' : 'paid',
            'status' => $nextStatus,
        ]);

        if (! $isLost) {
            $borrowing->book->update([
                'stok_tersedia' => $borrowing->book->stok_tersedia + 1,
            ]);
        }

        if ($validated['kondisi_kembali'] !== 'baik') {
            $borrowing->book->update([
                'kondisi' => $validated['kondisi_kembali'],
            ]);
        }

        $message = 'Pengembalian buku berhasil diproses.';
        if ($totalDenda > 0) {
            $message .= ' Denda total: Rp '.number_format($totalDenda, 0, ',', '.').' (status: menunggu pembayaran).';
        }

        $notificationMessage = 'Pengembalian buku "'.$borrowing->book->judul.'" telah diproses admin.';
        if ($totalDenda > 0) {
            $notificationMessage .= ' Total denda: Rp '.number_format($totalDenda, 0, ',', '.').'.';
        }

        $this->notifyBorrowingAction(
            $borrowing->loadMissing('book'),
            'Pengembalian diproses',
            $notificationMessage
        );

        $this->sendReturnApprovedEmail($borrowing);

        if ($totalDenda > 0) {
            $this->sendFineInvoiceEmail($borrowing);
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
            ->orderByDesc('tanggal_peminjaman')
            ->orderByDesc('id')
            ->paginate(10);

        $borrowingsCount = Borrowing::where('user_id', $userId)->count();
        $activeBorrowingsCount = Borrowing::where('user_id', $userId)
            ->whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])
            ->count();
        $totalFines = Borrowing::where('user_id', $userId)
            ->where('denda', '>', 0)
            ->where('fine_payment_status', 'unpaid')
            ->sum('denda');

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
            ->orderByDesc('tanggal_peminjaman')
            ->orderByDesc('id')
            ->paginate(8);

        $returnableCount = Borrowing::where('user_id', $userId)
            ->where('status', Borrowing::STATUS_ACTIVE)
            ->count();

        $waitingApprovalCount = Borrowing::where('user_id', $userId)
            ->where('status', Borrowing::STATUS_RETURN_PENDING)
            ->count();

        $recentHistory = Borrowing::where('user_id', $userId)
            ->with('book')
            ->orderByDesc('tanggal_peminjaman')
            ->orderByDesc('id')
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

        if (
            $borrowing->denda <= 0
            || ! in_array(
                $borrowing->status,
                [Borrowing::STATUS_WAITING_PAYMENT, Borrowing::STATUS_RETURNED, Borrowing::STATUS_LOST],
                true
            )
        ) {
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

        if (
            $borrowing->denda <= 0
            || ! in_array(
                $borrowing->status,
                [Borrowing::STATUS_WAITING_PAYMENT, Borrowing::STATUS_RETURNED, Borrowing::STATUS_LOST],
                true
            )
        ) {
            return redirect()->route('borrowing.history')->with('error', 'Transaksi ini tidak memiliki denda yang harus dibayar.');
        }

        if ($borrowing->fine_payment_status === 'paid') {
            return redirect()->route('borrowing.history')->with('success', 'Denda transaksi ini sudah dibayar.');
        }

        $validated = $request->validate([
            'fine_payment_note' => 'nullable|string|max:255',
        ]);

        $nextStatusAfterPayment = $borrowing->status === Borrowing::STATUS_WAITING_PAYMENT
            ? Borrowing::STATUS_RETURNED
            : $borrowing->status;

        $borrowing->update([
            'fine_payment_status' => 'paid',
            'fine_payment_method' => 'QRIS',
            'fine_payment_note' => $validated['fine_payment_note'] ?? null,
            'fine_paid_at' => now(),
            'status' => $nextStatusAfterPayment,
        ]);

        $fineDetail = $this->fineDetailText($borrowing);

        $this->notifyBorrowingAction(
            $borrowing->loadMissing('book'),
            'Pembayaran denda dikonfirmasi',
            'Pembayaran denda untuk buku "'.$borrowing->book->judul.'" berhasil dikonfirmasi melalui QRIS. '.$fineDetail
        );
        $this->notifyAdmins(
            $borrowing,
            'Konfirmasi pembayaran denda',
            'Siswa '.$borrowing->user->name.' telah mengonfirmasi pembayaran denda untuk buku "'.$borrowing->book->judul.'". '.$fineDetail,
            route('borrowings.index')
        );
        $this->sendFinePaidEmail($borrowing);

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
            ->orderByDesc('tanggal_peminjaman')
            ->orderByDesc('id')
            ->get();

        return [
            'user' => $user,
            'borrowings' => $borrowings,
            'generatedAt' => now(),
            'borrowingsCount' => $borrowings->count(),
            'activeBorrowingsCount' => $borrowings->whereIn('status', [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURN_PENDING])->count(),
            'totalFines' => $borrowings
                ->where('denda', '>', 0)
                ->filter(function ($borrowing) {
                    return $borrowing->fine_payment_status === 'unpaid';
                })
                ->sum('denda'),
        ];
    }

    /**
     * Create a database notification for borrowing action.
     */
    private function notifyBorrowingAction(Borrowing $borrowing, string $title, string $message): void
    {
        $borrowing->user->notify(new BorrowingActionNotification($borrowing, $title, $message));
    }

    /**
     * Notify all admins about student actions.
     */
    private function notifyAdmins(Borrowing $borrowing, string $title, string $message, ?string $targetUrl = null): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new BorrowingActionNotification($borrowing, $title, $message, $targetUrl));
        }
    }

    /**
     * Send email when return request has been approved by admin.
     */
    private function sendReturnApprovedEmail(Borrowing $borrowing): void
    {
        if (! $borrowing->user?->email) {
            return;
        }

        $borrowing->user->notify(new BorrowingReturnApprovedEmailNotification($borrowing));
    }

    /**
     * Send fine invoice email for late/damage/lost case.
     */
    private function sendFineInvoiceEmail(Borrowing $borrowing): void
    {
        if (! $borrowing->user?->email) {
            return;
        }

        $borrowing->user->notify(new BorrowingFineInvoiceEmailNotification($borrowing));
    }

    /**
     * Send fine paid confirmation email for student.
     */
    private function sendFinePaidEmail(Borrowing $borrowing): void
    {
        if (! $borrowing->user?->email) {
            return;
        }

        $borrowing->user->notify(new BorrowingFinePaidEmailNotification($borrowing));
    }

    /**
     * Build human-readable fine detail text for notifications.
     */
    private function fineDetailText(Borrowing $borrowing): string
    {
        $lateFine = (float) ($borrowing->denda_keterlambatan ?? 0);
        $damageFine = (float) ($borrowing->denda_kerusakan ?? 0);
        $parts = [];

        if ($lateFine > 0) {
            $parts[] = 'Denda keterlambatan: Rp '.number_format($lateFine, 0, ',', '.');
        }

        if ($damageFine > 0) {
            $parts[] = 'Denda kerusakan/kehilangan: Rp '.number_format($damageFine, 0, ',', '.');
        }

        if (empty($parts)) {
            return 'Jenis denda: umum.';
        }

        $detail = 'Rincian denda - '.implode('; ', $parts).'.';
        $note = trim((string) ($borrowing->keterangan ?? ''));

        if ($note !== '') {
            $detail .= ' Keterangan: '.$note.'.';
        }

        return $detail;
    }
}
