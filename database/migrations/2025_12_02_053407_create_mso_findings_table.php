<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsoFindingsTable extends Migration
{
    public function up()
    {
        Schema::create('mso_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mso_transaction_id')->constrained('mso_transactions')->onDelete('cascade');
            $table->string('sub_id');
            $table->unique(['mso_transaction_id', 'sub_id']);
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
            $table->foreignId('material_master_id')->nullable()->constrained('material_master')->nullOnDelete();
            $table->text('temuan');
            $table->text('action')->nullable();
            
            $table->string('status_perbaikan', 50)->default('Pending');
            $table->check("status_perbaikan IN ('Pending','Done','On Hold')");
            
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mso_findings');
    }
}