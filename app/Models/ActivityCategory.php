<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityCategory extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    // Created By Relation
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Updated By Relation
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'activity_category_id', 'id');
    }
}
