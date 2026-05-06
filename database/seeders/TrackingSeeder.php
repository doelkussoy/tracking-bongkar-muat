<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tracking; // Pastikan model ini ada
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class TrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan 'id_ID' untuk data palsu berbahasa Indonesia (jika diperlukan)
        $faker = Faker::create('id_ID');

        // Hapus data lama (opsional, tapi bagus untuk demo)
        Tracking::truncate();

        // Daftar nama vendor pengiriman/logistik di Indonesia
        $vendorList = [
            'JNE Express',
            'J&T Express',
            'SiCepat',
            'Anteraja',
            'Pos Indonesia',
            'Indah Logistik',
            'Deliveree',
            'Lalamove',
            'GoBox',
            'GrabExpress',
            'Wahana',
            'Paxel',
            'Baraka',
            'Rosalia Express',
            'Ninja Xpress'
        ];

        // Buat 15 data acak
        for ($i = 0; $i < 15; $i++) {
            
            // 50/50 kemungkinan "Selesai" atau "Sedang Berlangsung"
            $isCompleted = (bool)rand(0, 1); 

            // Atur waktu dasar (mulai)
            $security_start = Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 24));
            
            if ($isCompleted) {
                // --- BUAT DATA YANG SUDAH SELESAI ---
                // Data ini akan memiliki semua timestamp terisi

                $security_end = (clone $security_start)->addMinutes(rand(15, 60));
                $loading_start = (clone $security_end)->addMinutes(rand(10, 30));
                $loading_end = (clone $loading_start)->addHours(rand(1, 4));
                $ttb_start = (clone $loading_end)->addMinutes(rand(10, 30));
                $ttb_end = (clone $ttb_start)->addMinutes(rand(30, 60));

                Tracking::create([
                    'vehicle_name' => $faker->randomElement($vendorList), // <-- PERUBAHAN
                    'plate_number' => $faker->bothify('? ### ???'),
                    'description'  => 'Bongkar', // <-- PERUBAHAN
                    'security_start' => $security_start,
                    'security_end' => $security_end,
                    'loading_start' => $loading_start,
                    'loading_end' => $loading_end,
                    'ttb_start' => $ttb_start,
                    'ttb_end' => $ttb_end,
                    'current_stage' => 'completed', // UI Anda akan menampilkan ini sebagai "Selesai"
                    'created_at' => $security_start,
                    'updated_at' => $ttb_end,
                ]);

            } else {
                // --- BUAT DATA SEDANG BERLANGSUNG ---
                // Data ini hanya akan terisi sebagian (misal, baru selesai security)

                $security_end = (clone $security_start)->addMinutes(rand(15, 60));

                Tracking::create([
                    'vehicle_name' => $faker->randomElement($vendorList), // <-- PERUBAHAN
                    'plate_number' => $faker->bothify('? ### ???'),
                    'description'  => 'Bongkar', // <-- PERUBAHAN
                    'security_start' => $security_start,
                    'security_end' => $security_end,
                    'loading_start' => null, // Belum mulai
                    'loading_end' => null,
                    'ttb_start' => null,
                    'ttb_end' => null,
                    'current_stage' => 'security_done', // UI Anda akan menampilkan ini sebagai "Sedang Berlangsung"
                    'created_at' => $security_start,
                    'updated_at' => $security_end,
                ]);
            }
        }
    }
}