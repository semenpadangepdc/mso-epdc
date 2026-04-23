<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdtransColumnsToMsoTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('mso_transactions', function (Blueprint $table) {
            $table->string('id_trans', 20)->nullable()->after('id');
            $table->string('sub_id', 25)->nullable()->after('id_trans');
            $table->string('component')->nullable()->after('nomenclature_id');
        });
    }

    public function down()
    {
        Schema::table('mso_transactions', function (Blueprint $table) {
            $table->dropColumn(['id_trans', 'sub_id', 'component']);
        });
    }
}

