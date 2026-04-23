<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MsoTransaction extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'id_trans','no_mso','user_id','plant_id','area_id','nomenclature_id','description','status_peralatan',
        'maintenance_type_id','status_pekerjaan','start_date','finish_date','start_hour','finish_hour',
        'total_duration','keterangan'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function plant() { return $this->belongsTo(Plant::class); }
    public function area() { return $this->belongsTo(Area::class); }
    public function nomenclature() { return $this->belongsTo(Nomenclature::class); }
    public function maintenanceType() { return $this->belongsTo(MaintenanceType::class); }
    public function findings() { return $this->hasMany(MsoFinding::class, 'mso_transaction_id'); }
    public function photos() { return $this->hasMany(MsoPhoto::class, 'mso_transaction_id'); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'no_mso',
                'status_pekerjaan',
                'status_peralatan',
                'maintenance_type_id',
                'start_date',
                'finish_date',
            ])
            ->useLogName('mso')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
