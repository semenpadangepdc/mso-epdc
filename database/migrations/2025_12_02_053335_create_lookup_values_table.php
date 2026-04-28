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
            $table->string('type');
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('lookup_values');
    }
}