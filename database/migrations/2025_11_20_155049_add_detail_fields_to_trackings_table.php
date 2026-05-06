<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            // Identitas perusahaan & kendaraan
            $table->string('company_name')->nullable()->after('vehicle_name'); // Nama Instansi / Vendor
            $table->string('vehicle_kind')->nullable()->after('plate_number'); // Jenis Kendaraan
            $table->string('destination')->nullable()->after('vehicle_kind');  // Tujuan

            // Detail supir
            $table->string('driver_phone')->nullable()->after('driver_name');
            $table->string('driver_identity')->nullable()->after('driver_phone'); // KTP / SIM

            // Khusus proses BONGKAR
            $table->string('sj_number')->nullable()->after('description');   // No. Surat Jalan
            $table->string('item_name')->nullable()->after('sj_number');     // Nama Barang
            $table->string('item_quantity')->nullable()->after('item_name'); // Jumlah Barang
        });
    }

    public function down(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'vehicle_kind',
                'destination',
                'driver_phone',
                'driver_identity',
                'sj_number',
                'item_name',
                'item_quantity',
            ]);
        });
    }
};
