@extends('layouts.app')

@section('title', 'Manajemen Peminjaman')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="page-title">Manajemen Peminjaman</h1>
        <p class="page-subtitle">Verifikasi permintaan peminjaman dan pengembalian siswa.</p>
    </div>

    <div class="glass-card p-5">
        <form method="GET" action="{{ route('borrowings.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa atau judul buku" class="form-control md:col-span-2">
            <select name="status" class="form-control">
                <option value="">Semua status</option>
                <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="menunggu_pengembalian" {{ request('status') === 'menunggu_pengembalian' ? 'selected' : '' }}>Menunggu Pengembalian</option>
                <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="hilang" {{ request('status') === 'hilang' ? 'selected' : '' }}>Hilang</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn-primary w-full">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Siswa</th>
                        <th class="px-6 py-3 text-left font-semibold">Buku</th>
                        <th class="px-6 py-3 text-left font-semibold">Tgl Ajukan</th>
                        <th class="px-6 py-3 text-left font-semibold">Rencana Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-left font-semibold">Kondisi Kembali</th>
                        <th class="px-6 py-3 text-left font-semibold">Catatan</th>
                        <th class="px-6 py-3 text-right font-semibold">Denda</th>
                        <th class="px-6 py-3 text-center font-semibold">Pembayaran</th>
                        <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($borrowings as $borrowing)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $borrowing->user->name }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $borrowing->book->judul }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($borrowing->status === 'menunggu_persetujuan')
                                    <span class="status-badge status-pending">Menunggu Persetujuan</span>
                                @elseif($borrowing->status === 'aktif')
                                    <span class="status-badge status-active">Aktif</span>
                                @elseif($borrowing->status === 'menunggu_pengembalian')
                                    <span class="status-badge status-return-pending">Menunggu Pengembalian</span>
                                @elseif($borrowing->status === 'menunggu_pembayaran')
                                    <span class="status-badge status-pending">Menunggu Pembayaran</span>
                                @elseif($borrowing->status === 'dikembalikan')
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                @elseif($borrowing->status === 'hilang')
                                    <span class="status-badge status-rejected">Hilang</span>
                                @else
                                    <span class="status-badge status-rejected">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($borrowing->kondisi_kembali)
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $borrowing->kondisi_kembali === 'baik' ? 'bg-emerald-100 text-emerald-700' : ($borrowing->kondisi_kembali === 'rusak ringan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ ucfirst($borrowing->kondisi_kembali) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $borrowing->keterangan ?: '-' }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($borrowing->denda > 0)
                                    @if($borrowing->fine_payment_status === 'paid')
                                        <span class="status-badge status-returned">Lunas</span>
                                        @if($borrowing->fine_paid_at)
                                            <p class="mt-1 text-[11px] text-slate-500">{{ $borrowing->fine_paid_at->format('d M Y H:i') }}</p>
                                        @endif
                                    @else
                                        <span class="status-badge status-pending">Belum Dibayar</span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($borrowing->status === 'menunggu_persetujuan')
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <form action="{{ route('borrowing.approve', $borrowing) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn-approve">Setujui</button>
                                        </form>
                                        <form action="{{ route('borrowing.reject', $borrowing) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn-reject">Tolak</button>
                                        </form>
                                    </div>
                                @elseif($borrowing->status === 'menunggu_pengembalian')
                                    <button
                                        type="button"
                                        class="action-btn action-btn-approve js-open-verify-modal"
                                        data-action="{{ route('borrowing.process-return-admin', $borrowing) }}"
                                        data-student="{{ $borrowing->user->name }}"
                                        data-book="{{ $borrowing->book->judul }}"
                                        data-borrow-date="{{ optional($borrowing->tanggal_peminjaman)->format('d M Y') }}"
                                        data-plan-return="{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}"
                                        data-note="{{ $borrowing->keterangan ?: '-' }}"
                                    >
                                        Verifikasi
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-slate-500">Tidak ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($borrowings->hasPages())
        <div>{{ $borrowings->links() }}</div>
    @endif
</div>

<div id="verify-return-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-2xl rounded-2xl border border-slate-200 bg-white p-5 shadow-xl">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Verifikasi Pengembalian Buku</h2>
                <p class="mt-1 text-sm text-slate-600">Periksa kondisi buku, isi denda jika perlu, lalu simpan verifikasi.</p>
            </div>
            <button type="button" id="verify-modal-close-top" class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-700">Tutup</button>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm md:grid-cols-2">
            <p><span class="font-semibold text-slate-800">Siswa:</span> <span id="verify-detail-student">-</span></p>
            <p><span class="font-semibold text-slate-800">Judul Buku:</span> <span id="verify-detail-book">-</span></p>
            <p><span class="font-semibold text-slate-800">Tgl Pinjam:</span> <span id="verify-detail-borrow-date">-</span></p>
            <p><span class="font-semibold text-slate-800">Rencana Kembali:</span> <span id="verify-detail-plan-return">-</span></p>
            <p class="md:col-span-2"><span class="font-semibold text-slate-800">Catatan Sebelumnya:</span> <span id="verify-detail-note">-</span></p>
        </div>

        <form id="verify-return-form" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="verify_kondisi_kembali" class="form-label">Kondisi Buku Saat Kembali</label>
                <select id="verify_kondisi_kembali" name="kondisi_kembali" class="form-control">
                    <option value="baik">Baik</option>
                    <option value="rusak ringan">Rusak ringan</option>
                    <option value="rusak berat">Rusak berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
            <div>
                <label for="verify_denda_kerusakan" class="form-label">Denda Kerusakan/Kehilangan</label>
                <input id="verify_denda_kerusakan" type="number" min="0" step="500" name="denda_kerusakan" class="form-control" placeholder="Contoh: 25000">
                <p class="mt-1 text-xs text-slate-500">Isi 0 jika kondisi buku baik.</p>
            </div>
            <div>
                <label for="verify_keterangan" class="form-label">Keterangan Kerusakan/Kehilangan</label>
                <textarea id="verify_keterangan" name="keterangan" rows="3" class="form-control" placeholder="Contoh: Halaman 10-15 sobek / Buku hilang"></textarea>
            </div>

            <div class="flex flex-col gap-2 border-t border-slate-200 pt-4 sm:flex-row sm:justify-end">
                <button type="button" id="verify-modal-cancel" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan Verifikasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('verify-return-modal');
        const form = document.getElementById('verify-return-form');
        const condition = document.getElementById('verify_kondisi_kembali');
        const fineInput = document.getElementById('verify_denda_kerusakan');
        const noteInput = document.getElementById('verify_keterangan');
        const openButtons = document.querySelectorAll('.js-open-verify-modal');
        const closeTop = document.getElementById('verify-modal-close-top');
        const closeCancel = document.getElementById('verify-modal-cancel');

        function toggleRequirement() {
            const needsDetails = condition.value !== 'baik';
            fineInput.required = needsDetails;
            noteInput.required = needsDetails;
            if (!needsDetails && fineInput.value === '') {
                fineInput.value = '0';
            }
        }

        function openModal(button) {
            form.action = button.dataset.action;
            document.getElementById('verify-detail-student').textContent = button.dataset.student || '-';
            document.getElementById('verify-detail-book').textContent = button.dataset.book || '-';
            document.getElementById('verify-detail-borrow-date').textContent = button.dataset.borrowDate || '-';
            document.getElementById('verify-detail-plan-return').textContent = button.dataset.planReturn || '-';
            document.getElementById('verify-detail-note').textContent = button.dataset.note || '-';

            condition.value = 'baik';
            fineInput.value = '0';
            noteInput.value = '';
            toggleRequirement();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        openButtons.forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        condition.addEventListener('change', toggleRequirement);
        closeTop.addEventListener('click', closeModal);
        closeCancel.addEventListener('click', closeModal);
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    })();
</script>
@endsection
