<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsoPhotosTable extends Migration
{
    public function up()
    {
        Schema::create('mso_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mso_transaction_id')->constrained('mso_transactions')->onDelete('cascade');
            $table->foreignId('finding_id')->nullable()->constrained('mso_findings')->nullOnDelete();

            $table->enum('type', ['before', 'after']);

            $table->string('drive_file_id')->nullable();
            $table->string('filename');
            $table->string('mime')->nullable();
            $table->bigInteger('filesize')->nullable();
            $table->longText('thumb_base64')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mso_photos');
    }
}