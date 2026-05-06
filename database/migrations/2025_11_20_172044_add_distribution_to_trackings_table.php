<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            // Setelah TTB selesai
            $table->dateTime('distribution_at')->nullable()->after('ttb_end');
            $table->string('distribution_officer')->nullable()->after('distribution_at');
        });
    }

    public function down(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->dropColumn(['distribution_at', 'distribution_officer']);
        });
    }
};
