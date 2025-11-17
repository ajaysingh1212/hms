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

class IpdBilling extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'ipd_billings';

    protected $appends = [
        'attechments',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const DISCOUNT_TYPE_SELECT = [
        'value'      => 'Value (Amount)',
        'percentage' => 'Percentage (%)',
    ];

    public const PAYMENT_TYPE_SELECT = [
        'cash'       => 'Cash',
        'upi'        => 'Upi',
        'card'       => 'Card',
        'netbanking' => 'Net Banking',
        'other'      => 'Other',
    ];

    protected $fillable = [
        'ipd_id',
        'created_at',
        'other_charges',
        'total',
        'discount_type',
        'paid',
        'due',
        'payment_type',
        'notes',
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

    public function ipd()
    {
        return $this->belongsTo(IpdAdmission::class, 'ipd_id');
    }

    public function getAttechmentsAttribute()
    {
        return $this->getMedia('attechments');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
