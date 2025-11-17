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

class OpdVisit extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'opd_visits';

    protected $appends = [
        'attechment',
    ];

    public const VISIT_TYPE_SELECT = [
        'new'      => 'New',
        'followup' => 'Followup',
    ];

    protected $dates = [
        'created_at',
        'visit_date',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'open'      => 'open',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'created_at',
        'visit_date',
        'visit_time',
        'symptoms',
        'diagnosis',
        'notes',
        'visit_type',
        'status',
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

    public function patient()
    {
        return $this->belongsTo(Appointment::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(AddDoctor::class, 'doctor_id');
    }

    public function getVisitDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setVisitDateAttribute($value)
    {
        $this->attributes['visit_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getAttechmentAttribute()
    {
        return $this->getMedia('attechment');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
