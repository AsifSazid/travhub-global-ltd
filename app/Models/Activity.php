<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function activityCategory()
    {
        return $this->hasMany(ActivityCategory::class, 'activity_category_id', 'id');
    }
}
