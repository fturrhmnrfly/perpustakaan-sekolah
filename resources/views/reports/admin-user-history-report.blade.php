<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Peminjaman Siswa</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #0f172a; font-size: 12px; }
        h1 { margin: 0 0 6px; font-size: 20px; }
        .meta { margin-bottom: 14px; color: #334155; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #e0f2fe; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Riwayat Peminjaman Siswa</h1>
    <div class="meta">
        Nama: {{ $user->name }}<br>
        Email: {{ $user->email }}<br>
        Dicetak: {{ $generatedAt->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th class="right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->book->judul }}</td>
                    <td>{{ $borrowing->tanggal_peminjaman->format('d M Y') }}</td>
                    <td>{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</td>
                    <td>{{ $borrowing->tanggal_kembali_aktual ? $borrowing->tanggal_kembali_aktual->format('d M Y') : '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}</td>
                    <td class="right">Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada riwayat peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($autoPrint ?? false)
        <script>
            window.onload = function () {
                window.print();
            };
        </script>
    @endif
</body>
</html>
