<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsoTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('mso_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('no_mso')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->foreignId('nomenclature_id')->constrained('nomenclatures')->onDelete('cascade');
            $table->text('description')->nullable();
            
            // PostgreSQL: gunakan VARCHAR dengan CHECK constraint
            $table->string('status_peralatan', 50)->default('Active Operation');
            $table->check("status_peralatan IN ('Active Operation','Ready Standby','Broken - Eliminated')");
            
            $table->foreignId('maintenance_type_id')->constrained('maintenance_types')->onDelete('cascade');
            
            $table->string('status_pekerjaan', 50)->default('Open');
            $table->check("status_pekerjaan IN ('Open','Partial Finish','Closed')");
            
            $table->dateTime('start_date')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->time('start_hour')->nullable();
            $table->time('finish_hour')->nullable();
            $table->decimal('total_duration', 8, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mso_transactions');
    }
}