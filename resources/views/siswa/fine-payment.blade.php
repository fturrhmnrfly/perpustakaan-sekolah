@extends('layouts.app')

@section('title', 'Bayar Denda')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <h1 class="page-title">Pembayaran Denda QRIS</h1>
        <p class="page-subtitle">Scan QRIS menggunakan aplikasi pembayaran apa saja (all payment), lalu konfirmasi pembayaran.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="section-card">
            <h2 class="text-lg font-bold text-slate-900">Detail Transaksi</h2>
            <div class="mt-4 space-y-2 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-800">Judul Buku:</span> {{ $borrowing->book->judul }}</p>
                <p><span class="font-semibold text-slate-800">Tanggal Kembali:</span> {{ optional($borrowing->tanggal_kembali_aktual)->format('d M Y') }}</p>
                <p><span class="font-semibold text-slate-800">Denda:</span> Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</p>
            </div>
            <div class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700">
                Pastikan nominal yang dibayar sesuai total denda agar transaksi tercatat dengan benar.
            </div>
        </section>

        <section class="section-card">
            <h2 class="text-lg font-bold text-slate-900">Scan QRIS</h2>
            <div class="mt-4 flex justify-center rounded-2xl border border-slate-200 bg-white p-4">
                <img src="{{ asset('images/qris-all-payment.svg') }}" alt="QRIS All Payment" class="h-64 w-64 object-contain">
            </div>
            <form action="{{ route('borrowing.fine-payment.process', $borrowing) }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="fine_payment_note" class="form-label">Catatan Pembayaran (Opsional)</label>
                    <input id="fine_payment_note" name="fine_payment_note" type="text" class="form-control" placeholder="Contoh: Dibayar via ShopeePay">
                </div>
                <button type="submit" class="btn-primary w-full">Saya Sudah Bayar</button>
            </form>
        </section>
    </div>
</div>
@endsection
