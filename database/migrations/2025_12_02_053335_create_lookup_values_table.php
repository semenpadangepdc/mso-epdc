<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLookupValuesTable extends Migration
{
    public function up()
    {
        Schema::create('lookup_values', function (Blueprint $table) {
            $table->id();
            $table->string('type');      // e.g. report_by, condition, source, prioritas
            $table->string('key');       // internal key
            $table->string('value');     // displayed value
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('lookup_values');
    }
}
