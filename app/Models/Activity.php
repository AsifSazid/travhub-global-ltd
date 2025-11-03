<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class,  'country_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,  'city_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function inclusions()
    {
        return $this->hasMany(Inclusion::class, 'activity_id', 'id');
    }

    public function packDestinationInfos()
    {
        return $this->hasMany(PackDestinationInfo::class, 'activity_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
