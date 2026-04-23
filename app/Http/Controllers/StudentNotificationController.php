<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentNotificationController extends Controller
{
    /**
     * Show notifications page for logged-in student.
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(12);

        return view('siswa.notifications', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()
            ->route('siswa.notifications.index')
            ->with('success', 'Semua notifikasi berhasil ditandai sudah dibaca.');
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()
            ->route('siswa.notifications.index')
            ->with('success', 'Semua notifikasi berhasil dihapus.');
    }

    /**
     * Delete selected notifications.
     */
    public function destroySelected(Request $request)
    {
        $validated = $request->validate([
            'notification_ids' => 'required|array|min:1',
            'notification_ids.*' => 'required|string',
        ]);

        auth()->user()
            ->notifications()
            ->whereIn('id', $validated['notification_ids'])
            ->delete();

        return redirect()
            ->route('siswa.notifications.index')
            ->with('success', 'Notifikasi terpilih berhasil dihapus.');
    }
}
