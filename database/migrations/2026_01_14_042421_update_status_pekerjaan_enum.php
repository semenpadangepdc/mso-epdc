<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL tidak support ALTER ENUM langsung
        // Jadi kita ubah ke VARCHAR dulu, lalu kembali ke ENUM
        
        DB::statement("ALTER TABLE `mso_transactions` 
            MODIFY `status_pekerjaan` VARCHAR(50) NOT NULL DEFAULT 'Open'");
        
        // Sekarang ubah kembali ke ENUM dengan nilai baru
        DB::statement("ALTER TABLE `mso_transactions` 
            MODIFY `status_pekerjaan` ENUM('Open','On Progress','Closed') NOT NULL DEFAULT 'Open'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM lama
        DB::statement("ALTER TABLE `mso_transactions` 
            MODIFY `status_pekerjaan` ENUM('Open','Partial Finish','Closed') NOT NULL DEFAULT 'Open'");
    }
};