<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentNotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route (for authenticated users)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student routes
    Route::middleware('role:siswa')->group(function () {
        // Browse and borrow books
        Route::get('/cari-buku', [BookController::class, 'search'])->name('books.search');
        Route::get('/buku/{book}/pinjam', [BorrowingController::class, 'borrow'])->name('books.borrow');
        Route::post('/buku/{book}/pinjam', [BorrowingController::class, 'store'])->name('borrowings.store');

        // Return books
        Route::get('/pengembalian-buku', [BorrowingController::class, 'returnsPage'])->name('borrowing.returns');
        Route::get('/peminjaman/{borrowing}/kembalikan', [BorrowingController::class, 'returnBook'])->name('borrowing.return');
        Route::post('/peminjaman/{borrowing}/kembalikan', [BorrowingController::class, 'processReturn'])->name('borrowing.process-return');

        // View borrowing history
        Route::get('/riwayat-peminjaman', [BorrowingController::class, 'history'])->name('borrowing.history');
        Route::get('/riwayat-peminjaman/cetak', [BorrowingController::class, 'printHistory'])->name('borrowing.history.print');
        Route::get('/riwayat-peminjaman/unduh-pdf', [BorrowingController::class, 'downloadHistoryPdf'])->name('borrowing.history.pdf');
        Route::get('/riwayat-peminjaman/{borrowing}/bayar-denda', [BorrowingController::class, 'showFinePayment'])->name('borrowing.fine-payment');
        Route::post('/riwayat-peminjaman/{borrowing}/bayar-denda', [BorrowingController::class, 'processFinePayment'])->name('borrowing.fine-payment.process');

        // Notifications
        Route::get('/notifikasi', [StudentNotificationController::class, 'index'])->name('siswa.notifications.index');
        Route::post('/notifikasi/tandai-dibaca', [StudentNotificationController::class, 'markAllAsRead'])->name('siswa.notifications.mark-all-read');
        Route::delete('/notifikasi/hapus-semua', [StudentNotificationController::class, 'destroyAll'])->name('siswa.notifications.destroy-all');
        Route::delete('/notifikasi/hapus-terpilih', [StudentNotificationController::class, 'destroySelected'])->name('siswa.notifications.destroy-selected');
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        // Books management
        Route::resource('books', BookController::class);

        // Categories management
        Route::resource('categories', CategoryController::class)->except('show');

        // Students management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}/borrowing-history', [UserController::class, 'showBorrowingHistory'])->name('users.borrowing-history');
        Route::get('/users/{user}/borrowing-history/cetak', [UserController::class, 'printBorrowingHistory'])->name('users.borrowing-history.print');
        Route::get('/users/{user}/borrowing-history/unduh-pdf', [UserController::class, 'downloadBorrowingHistoryPdf'])->name('users.borrowing-history.pdf');

        // Borrowing management
        Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
        Route::post('/borrowing/{borrowing}/approve', [BorrowingController::class, 'approveBorrowing'])->name('borrowing.approve');
        Route::post('/borrowing/{borrowing}/reject', [BorrowingController::class, 'rejectBorrowing'])->name('borrowing.reject');
        Route::post('/borrowing/{borrowing}/return-admin', [BorrowingController::class, 'adminProcessReturn'])->name('borrowing.process-return-admin');

        // Admin notifications
        Route::get('/admin/notifikasi', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
        Route::post('/admin/notifikasi/tandai-dibaca', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');
        Route::delete('/admin/notifikasi/hapus-semua', [AdminNotificationController::class, 'destroyAll'])->name('admin.notifications.destroy-all');
        Route::delete('/admin/notifikasi/hapus-terpilih', [AdminNotificationController::class, 'destroySelected'])->name('admin.notifications.destroy-selected');
    });
});

require __DIR__.'/auth.php';
