<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddDoctor extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'add_doctors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const AVAILABLE_DAYS_RADIO = [
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
        'sunday'    => 'Sunday',
    ];

    protected $fillable = [
        'select_department_id',
        'doctor_name',
        'available_days',
        'appointment_slot_duration',
        'max_patients_per_day',
        'doctor_fee',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
        'phone',
        'phone_alt',
        'experience',
        'qualifications',
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

    public function doctorOpdVisits()
    {
        return $this->hasMany(OpdVisit::class, 'doctor_id', 'id');
    }

    public function select_department()
    {
        return $this->belongsTo(DepartmentName::class, 'select_department_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
