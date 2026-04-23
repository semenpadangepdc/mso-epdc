<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsoPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'mso_transaction_id',
        'finding_id',
        'type',
        'drive_file_id',
        'filename',
        'mime',
        'filesize',
        'thumb_base64',
    ];
    public function transaction() { return $this->belongsTo(MsoTransaction::class,'mso_transaction_id'); }
    public function finding() { return $this->belongsTo(MsoFinding::class,'finding_id'); }
}
