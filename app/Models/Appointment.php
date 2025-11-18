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

class Appointment extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'appointments';

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
    'available_days' => 'array',
    'doctor_slots' => 'array',
    ];

    public const APPOINTMENT_TYPE_SELECT = [
        'new'       => 'New',
        'follow-up' => 'Follow-up',
    ];

    public const STATUS_SELECT = [
        'Pending'   => 'Pending',
        'Booked'    => 'Booked',
        'Cancelled' => 'Cancelled by patient',
        'Completed' => 'Completed (doctor checked)',
        'No-show'   => 'patient did not come',
    ];

    protected $fillable = [
        'patient_number',
        'department_id',
        'doctor_id',
        'available_slots_id',
        'patient_name',
        'mobile_number',
        'date',
        'reason_for_visit',
        'appointment_type',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
        'available_days',
        'doctor_slots',
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

    public function patientOpdVisits()
    {
        return $this->hasMany(OpdVisit::class, 'patient_id', 'id');
    }

    public function patientIpdAdmissions()
    {
        return $this->hasMany(IpdAdmission::class, 'patient_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(DepartmentName::class, 'department_id');
    }

    public function doctor()
    {
        return $this->belongsTo(AddDoctor::class, 'doctor_id');
    }

    public function available_slots()
    {
        return $this->belongsTo(AppointmentSlot::class, 'available_slots_id');
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
