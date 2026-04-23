@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="page-title">Notifikasi</h1>
            <p class="page-subtitle">Semua tindakan terbaru dari proses peminjaman, pengembalian, dan pembayaran ada di sini.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('siswa.notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="action-btn action-btn-view">Tandai Sudah Dibaca</button>
            </form>
            <form method="POST" action="{{ route('siswa.notifications.destroy-all') }}" onsubmit="return confirm('Yakin ingin menghapus semua notifikasi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn action-btn-delete">Hapus Semua</button>
            </form>
        </div>
    </div>

    <div class="table-wrap">
        <form method="POST" action="{{ route('siswa.notifications.destroy-selected') }}" id="selected-delete-form" onsubmit="return confirm('Hapus notifikasi yang dipilih?')">
            @csrf
            @method('DELETE')

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-4 py-3 sm:px-6">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input id="select-all-notifications" type="checkbox" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    Pilih semua
                </label>
                <button type="submit" class="action-btn action-btn-delete" id="delete-selected-button" disabled>
                    Hapus yang Dipilih
                </button>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="flex gap-3 px-4 py-4 transition hover:bg-slate-50/80 sm:px-6 {{ $isUnread ? 'bg-sky-50/40' : '' }}">
                        <div class="pt-1">
                            <input
                                type="checkbox"
                                name="notification_ids[]"
                                value="{{ $notification->id }}"
                                class="notification-checkbox rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                            >
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-slate-900">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                @if($isUnread)
                                    <span class="status-badge status-active">Baru</span>
                                @else
                                    <span class="status-badge status-returned">Sudah Dibaca</span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ $notification->data['message'] ?? '-' }}
                            </p>
                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                <span class="text-xs font-medium text-slate-500">
                                    {{ $notification->created_at->translatedFormat('d M Y H:i') }}
                                </span>
                                @if(!empty($notification->data['target_url']))
                                    <a href="{{ $notification->data['target_url'] }}" class="text-xs font-semibold text-sky-700 hover:text-sky-800">Lihat detail</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-500">
                        Belum ada notifikasi.
                    </div>
                @endforelse
            </div>
        </form>
    </div>

    @if($notifications->hasPages())
        <div>{{ $notifications->links() }}</div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all-notifications');
        const deleteSelectedButton = document.getElementById('delete-selected-button');
        const checkboxes = Array.from(document.querySelectorAll('.notification-checkbox'));

        function refreshButtonState() {
            const selectedCount = checkboxes.filter((item) => item.checked).length;
            deleteSelectedButton.disabled = selectedCount === 0;
            deleteSelectedButton.classList.toggle('opacity-50', selectedCount === 0);
            deleteSelectedButton.classList.toggle('cursor-not-allowed', selectedCount === 0);
            selectAll.checked = checkboxes.length > 0 && selectedCount === checkboxes.length;
        }

        selectAll.addEventListener('change', function () {
            checkboxes.forEach((item) => {
                item.checked = selectAll.checked;
            });
            refreshButtonState();
        });

        checkboxes.forEach((item) => {
            item.addEventListener('change', refreshButtonState);
        });

        refreshButtonState();
    });
</script>
@endsection
