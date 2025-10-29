<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
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

    public function packDestinationInfos()
    {
        return $this->hasMany(PackDestinationInfo::class, 'package_id');
    }

    public function packInclusions()
    {
        return $this->hasMany(PackInclusion::class, 'package_id');
    }

    public function packItenaries()
    {
        return $this->hasMany(PackItenaries::class, 'package_id');
    }

    public function PackPrices()
    {
        return $this->hasMany(PackPrice::class, 'package_id');
    }

    public function PackQuatDetails()
    {
        return $this->hasMany(PackQuatDetail::class, 'package_id');
    }

    public function PackAccomoDetails()
    {
        return $this->hasMany(PackAccomoDetail::class, 'package_id');
    }
}
