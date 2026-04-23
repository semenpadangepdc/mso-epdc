<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MsoFinding extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['mso_transaction_id','sub_id','component_id','material_master_id','temuan','action','status_perbaikan'];

    public function transaction() { return $this->belongsTo(MsoTransaction::class,'mso_transaction_id'); }
    public function component() { return $this->belongsTo(Component::class); }
    public function material() { return $this->belongsTo(MaterialMaster::class,'material_master_id'); }
    public function photos() { return $this->hasMany(MsoPhoto::class,'finding_id'); }
    public function materialMaster()
    {
        return $this->belongsTo(\App\Models\MaterialMaster::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'material_master_id',
                'status_perbaikan',
                'action'
            ])
            ->useLogName('finding')
            ->logOnlyDirty();
    }

}
