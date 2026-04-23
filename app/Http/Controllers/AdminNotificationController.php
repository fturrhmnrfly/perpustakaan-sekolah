<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Show notifications page for logged-in admin.
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(12);

        return view('admin.notifications', [
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
            ->route('admin.notifications.index')
            ->with('success', 'Semua notifikasi admin berhasil ditandai sudah dibaca.');
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Semua notifikasi admin berhasil dihapus.');
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
            ->route('admin.notifications.index')
            ->with('success', 'Notifikasi admin terpilih berhasil dihapus.');
    }
}
