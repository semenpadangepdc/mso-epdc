<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // PostgreSQL: Cek apakah foreign key masih ada, jika ada hapus dulu constraint-nya
        // Atau gunakan DROP ... CASCADE
        
        // Opsi 1: Hapus constraint foreign key terlebih dahulu
        $foreignKeyExists = DB::select("
            SELECT 1 
            FROM information_schema.table_constraints 
            WHERE constraint_name = 'mso_findings_material_master_id_foreign'
            AND table_name = 'mso_findings'
            AND constraint_type = 'FOREIGN KEY'
        ");
        
        if (!empty($foreignKeyExists)) {
            DB::statement('ALTER TABLE mso_findings DROP CONSTRAINT mso_findings_material_master_id_foreign');
        }
        
        // Opsi 2: Gunakan DROP TABLE ... CASCADE (lebih sederhana tapi akan menghapus constraint juga)
        // DB::statement('DROP TABLE IF EXISTS material_master CASCADE');
        
        Schema::dropIfExists('material_master');

        Schema::create('material_master', function (Blueprint $table) {
            $table->id();

            $table->string('material_code')->unique();
            $table->string('material_description');
            $table->text('long_text')->nullable();

            $table->string('base_uom', 50)->nullable();
            $table->string('mrp_type', 50)->nullable();

            $table->decimal('price', 15, 2)->nullable();

            $table->string('material_group', 100)->nullable();
            $table->string('gl_account', 50)->nullable();

            $table->integer('safety_stock')->nullable();
            $table->boolean('critical_part')->default(false);

            $table->timestamps();
        });
        
        // Kembalikan foreign key ke mso_findings
        // Perhatikan: kolom material_master_id di tabel mso_findings harus bertipe bigint
        DB::statement('
            ALTER TABLE mso_findings 
            ADD CONSTRAINT mso_findings_material_master_id_foreign 
            FOREIGN KEY (material_master_id) 
            REFERENCES material_master(id) 
            ON DELETE SET NULL
        ');
    }

    public function down(): void
    {
        // Hapus foreign key constraint terlebih dahulu jika ada
        $foreignKeyExists = DB::select("
            SELECT 1 
            FROM information_schema.table_constraints 
            WHERE constraint_name = 'mso_findings_material_master_id_foreign'
            AND table_name = 'mso_findings'
            AND constraint_type = 'FOREIGN KEY'
        ");
        
        if (!empty($foreignKeyExists)) {
            DB::statement('ALTER TABLE mso_findings DROP CONSTRAINT mso_findings_material_master_id_foreign');
        }
        
        Schema::dropIfExists('material_master');
    }
};