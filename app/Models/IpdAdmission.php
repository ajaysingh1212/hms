<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class IpdAdmission extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'ipd_admissions';

    protected $appends = [
        'attechment',
    ];

    protected $dates = [
        'admission_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'admitted'    => 'admitted',
        'discharged'  => 'discharged',
        'transferred' => 'transferred',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'admission_date',
        'admission_time',
        'room_id',
        'bed_id',
        'condition_on_admission',
        'reason',
        'status',
        'created_at',
        'ipd_number',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function ipdIpdMedications()
    {
        return $this->hasMany(IpdMedication::class, 'ipd_id', 'id');
    }

    public function ipdIpdVitals()
    {
        return $this->hasMany(IpdVital::class, 'ipd_id', 'id');
    }

    public function ipdIpdTests()
    {
        return $this->hasMany(IpdTest::class, 'ipd_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Appointment::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(AddDoctor::class, 'doctor_id');
    }

    public function getAdmissionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setAdmissionDateAttribute($value)
    {
        $this->attributes['admission_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function room()
    {
        return $this->belongsTo(IpdRoom::class, 'room_id');
    }

    public function bed()
    {
        return $this->belongsTo(IpdBed::class, 'bed_id');
    }

    public function getAttechmentAttribute()
    {
        return $this->getMedia('attechment')->last();
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
