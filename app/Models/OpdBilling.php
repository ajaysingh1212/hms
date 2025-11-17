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

class OpdBilling extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'opd_billings';

    protected $appends = [
        'attechment',
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
        'account'    => 'Account',
        'card'       => 'Card',
        'netbanking' => 'Netbanking',
        'other'      => 'Other',
    ];

    protected $fillable = [
        'opd_id',
        'total',
        'discount_type',
        'paid',
        'due',
        'payment_type',
        'notes',
        'created_at',
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

    public function opd()
    {
        return $this->belongsTo(OpdVisit::class, 'opd_id');
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
