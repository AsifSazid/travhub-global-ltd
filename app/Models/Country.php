<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }

    public function currencies()
    {
        return $this->hasMany(Currency::class, 'currency_id');
    }

    public function packDestinationInfos()
    {
        return $this->hasMany(PackDestinationInfo::class, 'activity_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'country_id', 'id');
    }
}
