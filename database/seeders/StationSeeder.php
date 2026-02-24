<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        // Data master digabung dari Excel dan kode Anda.
        // Format: Code => [Name, City, Province]
        // KYA di Excel ada yang tertulis Karanganyar dan Kroya, saya gunakan Kroya (nama umum KYA).

        $data = [
            // --- DKI Jakarta ---
            ['code' => 'PSE', 'name' => 'Pasar Senen', 'city' => 'Jakarta Pusat', 'province' => 'DKI Jakarta'],
            ['code' => 'JNG', 'name' => 'Jatinegara', 'city' => 'Jakarta Timur', 'province' => 'DKI Jakarta'],

            // --- Jawa Barat ---
            ['code' => 'BKS', 'name' => 'Bekasi', 'city' => 'Bekasi', 'province' => 'Jawa Barat'],
            ['code' => 'CKR', 'name' => 'Cikarang', 'city' => 'Bekasi', 'province' => 'Jawa Barat'],
            ['code' => 'KW',  'name' => 'Karawang', 'city' => 'Karawang', 'province' => 'Jawa Barat'],
            ['code' => 'CKP', 'name' => 'Cikampek', 'city' => 'Karawang', 'province' => 'Jawa Barat'],
            ['code' => 'HGL', 'name' => 'Haurgeulis', 'city' => 'Indramayu', 'province' => 'Jawa Barat'],
            ['code' => 'JTB', 'name' => 'Jatibarang', 'city' => 'Indramayu', 'province' => 'Jawa Barat'],
            ['code' => 'CN',  'name' => 'Cirebon', 'city' => 'Cirebon', 'province' => 'Jawa Barat'],
            ['code' => 'CNP', 'name' => 'Cirebon Prujakan', 'city' => 'Cirebon', 'province' => 'Jawa Barat'],
            ['code' => 'BBK', 'name' => 'Babakan', 'city' => 'Cirebon', 'province' => 'Jawa Barat'],

            // --- Jawa Tengah ---
            ['code' => 'BB',  'name' => 'Brebes', 'city' => 'Brebes', 'province' => 'Jawa Tengah'],
            ['code' => 'TG',  'name' => 'Tegal', 'city' => 'Tegal', 'province' => 'Jawa Tengah'],
            ['code' => 'PML', 'name' => 'Pemalang', 'city' => 'Pemalang', 'province' => 'Jawa Tengah'],
            ['code' => 'PK',  'name' => 'Pekalongan', 'city' => 'Pekalongan', 'province' => 'Jawa Tengah'],
            ['code' => 'WLR', 'name' => 'Weleri', 'city' => 'Kendal', 'province' => 'Jawa Tengah'],
            ['code' => 'SMC', 'name' => 'Semarang Poncol', 'city' => 'Semarang', 'province' => 'Jawa Tengah'],
            ['code' => 'SMT', 'name' => 'Semarang Tawang', 'city' => 'Semarang', 'province' => 'Jawa Tengah'],
            ['code' => 'TW',  'name' => 'Telawa', 'city' => 'Boyolali', 'province' => 'Jawa Tengah'],
            ['code' => 'SK',  'name' => 'Solo Jebres', 'city' => 'Surakarta', 'province' => 'Jawa Tengah'],
            ['code' => 'SLO', 'name' => 'Solo Balapan', 'city' => 'Surakarta', 'province' => 'Jawa Tengah'],
            ['code' => 'SR',  'name' => 'Sragen', 'city' => 'Sragen', 'province' => 'Jawa Tengah'],
            ['code' => 'KT',  'name' => 'Klaten', 'city' => 'Klaten', 'province' => 'Jawa Tengah'],
            ['code' => 'KTA', 'name' => 'Kutoarjo', 'city' => 'Purworejo', 'province' => 'Jawa Tengah'],
            ['code' => 'KWN', 'name' => 'Kutowinangun', 'city' => 'Kebumen', 'province' => 'Jawa Tengah'],
            ['code' => 'KM',  'name' => 'Kebumen', 'city' => 'Kebumen', 'province' => 'Jawa Tengah'],
            ['code' => 'GB',  'name' => 'Gombong', 'city' => 'Kebumen', 'province' => 'Jawa Tengah'],
            ['code' => 'KYA', 'name' => 'Kroya', 'city' => 'Cilacap', 'province' => 'Jawa Tengah'],
            ['code' => 'PWT', 'name' => 'Purwokerto', 'city' => 'Banyumas', 'province' => 'Jawa Tengah'],
            ['code' => 'BMA', 'name' => 'Bumiayu', 'city' => 'Brebes', 'province' => 'Jawa Tengah'],
            ['code' => 'PPK', 'name' => 'Prupuk', 'city' => 'Tegal', 'province' => 'Jawa Tengah'],

            // --- DIY ---
            ['code' => 'YK',  'name' => 'Yogyakarta', 'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            ['code' => 'LPN', 'name' => 'Lempuyangan', 'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            ['code' => 'WT',  'name' => 'Wates', 'city' => 'Kulon Progo', 'province' => 'DI Yogyakarta'],

            // --- Jawa Timur ---
            ['code' => 'WK',  'name' => 'Walikukun', 'city' => 'Ngawi', 'province' => 'Jawa Timur'],
            ['code' => 'NGW', 'name' => 'Ngawi', 'city' => 'Ngawi', 'province' => 'Jawa Timur'],
            ['code' => 'MAG', 'name' => 'Magetan', 'city' => 'Magetan', 'province' => 'Jawa Timur'],
            ['code' => 'MN',  'name' => 'Madiun', 'city' => 'Madiun', 'province' => 'Jawa Timur'],
            ['code' => 'CRB', 'name' => 'Caruban', 'city' => 'Madiun', 'province' => 'Jawa Timur'],
            ['code' => 'NJ',  'name' => 'Nganjuk', 'city' => 'Nganjuk', 'province' => 'Jawa Timur'],
            ['code' => 'KTS', 'name' => 'Kertosono', 'city' => 'Nganjuk', 'province' => 'Jawa Timur'],
            ['code' => 'JG',  'name' => 'Jombang', 'city' => 'Jombang', 'province' => 'Jawa Timur'],
            ['code' => 'MR',  'name' => 'Mojokerto', 'city' => 'Mojokerto', 'province' => 'Jawa Timur'],
            ['code' => 'SGU', 'name' => 'Surabaya Gubeng', 'city' => 'Surabaya', 'province' => 'Jawa Timur'],
            ['code' => 'KD',  'name' => 'Kediri', 'city' => 'Kediri', 'province' => 'Jawa Timur'],
            ['code' => 'TA',  'name' => 'Tulungagung', 'city' => 'Tulungagung', 'province' => 'Jawa Timur'],
            ['code' => 'BL',  'name' => 'Blitar', 'city' => 'Blitar', 'province' => 'Jawa Timur'],
            ['code' => 'WG',  'name' => 'Wlingi', 'city' => 'Blitar', 'province' => 'Jawa Timur'],
            ['code' => 'KPN', 'name' => 'Kepanjen', 'city' => 'Malang', 'province' => 'Jawa Timur'],
            ['code' => 'MLK', 'name' => 'Malang Kota Lama', 'city' => 'Malang', 'province' => 'Jawa Timur'],
            ['code' => 'ML',  'name' => 'Malang', 'city' => 'Malang', 'province' => 'Jawa Timur'],
        ];

        foreach ($data as $station) {
            // updateOrCreate akan mengecek berdasarkan 'code'.
            // Jika ada, update datanya. Jika tidak ada, buat baru.
            Station::updateOrCreate(
                ['code' => $station['code']], // Kunci pencarian (Unique)
                $station // Data yang akan disimpan/diupdate
            );
        }
    }
}
