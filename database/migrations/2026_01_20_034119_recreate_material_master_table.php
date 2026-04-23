<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ⛔ MATIKAN FOREIGN KEY CHECK
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

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

        // ✅ NYALAKAN LAGI
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('material_master');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
