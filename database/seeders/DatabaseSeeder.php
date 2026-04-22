<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.local',
            'no_identitas' => 'ADMIN001',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // Create student users
        User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad@siswa.local',
            'no_identitas' => '001',
            'phone' => '082123456789',
            'role' => 'siswa',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@siswa.local',
            'no_identitas' => '002',
            'phone' => '082123456790',
            'role' => 'siswa',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@siswa.local',
            'no_identitas' => '003',
            'phone' => '082123456791',
            'role' => 'siswa',
            'password' => Hash::make('password123'),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Buku cerita dan novel'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku pengetahuan dan referensi'],
            ['name' => 'Sains', 'description' => 'Buku tentang sains dan alam'],
            ['name' => 'Sejarah', 'description' => 'Buku tentang sejarah'],
            ['name' => 'Agama', 'description' => 'Buku tentang agama dan nilai'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create books
        $booksData = [
            [
                'judul' => 'Laskar Pelangi',
                'pengarang' => 'Andrea Hirata',
                'penerbit' => 'Bentang',
                'isbn' => '9789793062367',
                'category_id' => 1,
                'tahun_terbit' => 2005,
                'stok' => 5,
                'stok_tersedia' => 5,
                'deskripsi' => 'Kisah sepuluh anak SD di pulau Belitong yang berjuang untuk tetap bersekolah.',
                'kondisi' => 'baik',
                'lokasi' => 'Rak A1',
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'pengarang' => 'Ahmad Fuadi',
                'penerbit' => 'Gramedia',
                'isbn' => '9789793063517',
                'category_id' => 1,
                'tahun_terbit' => 2009,
                'stok' => 4,
                'stok_tersedia' => 4,
                'deskripsi' => 'Kisah menuntut ilmu di Pesantren Modern Gontor dan universitas di Mesir.',
                'kondisi' => 'baik',
                'lokasi' => 'Rak A2',
            ],
            [
                'judul' => 'Sains Dasar',
                'pengarang' => 'Dr. Bambang Setyadi',
                'penerbit' => 'Erlangga',
                'isbn' => '9789793063124',
                'category_id' => 3,
                'tahun_terbit' => 2015,
                'stok' => 8,
                'stok_tersedia' => 6,
                'deskripsi' => 'Panduan lengkap pembelajaran sains untuk SMP dan SMA.',
                'kondisi' => 'baik',
                'lokasi' => 'Rak B1',
            ],
            [
                'judul' => 'Sejarah Indonesia',
                'pengarang' => 'Prof. Ricklefs',
                'penerbit' => 'Serambi',
                'isbn' => '9789793062595',
                'category_id' => 4,
                'tahun_terbit' => 2010,
                'stok' => 6,
                'stok_tersedia' => 6,
                'deskripsi' => 'Sejarah panjang perjalanan nusantara dari masa kesultanan hingga modern.',
                'kondisi' => 'baik',
                'lokasi' => 'Rak B2',
            ],
            [
                'judul' => 'Nilai-Nilai Islam',
                'pengarang' => 'KH. Maimoen Zubair',
                'penerbit' => 'Zaitun',
                'isbn' => '9789793063421',
                'category_id' => 5,
                'tahun_terbit' => 2012,
                'stok' => 10,
                'stok_tersedia' => 9,
                'deskripsi' => 'Pembelajaran tentang nilai-nilai luhur dalam Islam untuk generasi muda.',
                'kondisi' => 'baik',
                'lokasi' => 'Rak C1',
            ],
        ];

        foreach ($booksData as $book) {
            Book::create($book);
        }
    }
}
