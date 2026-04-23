<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration {
    public function up()
    {
        $transactions = DB::table('mso_transactions')
            ->whereNull('id_trans')
            ->get();

        foreach ($transactions as $trx) {

            $date = Carbon::parse($trx->created_at)->format('Ymd');

            // ambil nomenclature code (3 huruf)
            $nom = DB::table('nomenclatures')
                ->where('id', $trx->nomenclature_id)
                ->value('name');

            $code = strtoupper(substr($nom ?? 'XXX', 0, 3));

            $idTrans = "ID-$code-$date-0001";

            DB::table('mso_transactions')
                ->where('id', $trx->id)
                ->update([
                    'id_trans' => $idTrans
                ]);
        }
    }

    public function down() {}
};
