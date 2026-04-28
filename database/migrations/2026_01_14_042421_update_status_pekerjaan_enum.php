<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: drop old CHECK constraint and add new one
        DB::statement("ALTER TABLE mso_transactions DROP CONSTRAINT IF EXISTS mso_transactions_status_pekerjaan_check");
        DB::statement("ALTER TABLE mso_transactions ALTER COLUMN status_pekerjaan SET DEFAULT 'Open'");
        DB::statement("ALTER TABLE mso_transactions ADD CONSTRAINT mso_transactions_status_pekerjaan_check CHECK (status_pekerjaan IN ('Open','On Progress','Closed'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE mso_transactions DROP CONSTRAINT IF EXISTS mso_transactions_status_pekerjaan_check");
        DB::statement("ALTER TABLE mso_transactions ADD CONSTRAINT mso_transactions_status_pekerjaan_check CHECK (status_pekerjaan IN ('Open','Partial Finish','Closed'))");
    }
};