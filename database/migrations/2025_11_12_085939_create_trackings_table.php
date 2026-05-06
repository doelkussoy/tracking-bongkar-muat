<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            
            // Data Kendaraan
            $table->string('vehicle_name');
            $table->string('plate_number');

            // 👇 PASTIKAN BARIS INI ADA
            $table->string('driver_name')->nullable();
            
            // Jenis Kegiatan (Bongkar/Muat) - Default 'bongkar'
            $table->string('type')->default('bongkar'); 
            
            $table->text('description')->nullable(); // Buat nullable jika optional

            // 1. Security Masuk
            $table->datetime('security_start')->nullable();
            $table->string('security_in_officer')->nullable(); // Petugas Masuk

            // 2. Proses Bongkar/Muat
            $table->datetime('loading_start')->nullable();
            $table->string('loading_start_officer')->nullable(); // Petugas Mulai Bongkar
            
            $table->datetime('loading_end')->nullable();
            $table->string('loading_end_officer')->nullable();   // Petugas Selesai Bongkar

            // 3. Proses TTB (Tanda Terima Barang)
            $table->datetime('ttb_start')->nullable();
            $table->string('ttb_start_officer')->nullable();     // Officer Mulai
            
            $table->datetime('ttb_end')->nullable();
            $table->string('ttb_end_officer')->nullable();       // Officer Selesai

            // 4. Security Keluar
            $table->datetime('security_end')->nullable();
            $table->string('security_out_officer')->nullable();  // Petugas Keluar

            // Status & Timestamp
            $table->string('current_stage');
            $table->timestamps();

            // Optimasi Database (Indexing)
            $table->index('plate_number');
            $table->index('current_stage');
            $table->index('type'); // Index juga tipe agar filter bongkar/muat cepat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};