<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackDestinationInfo extends Model
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

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
