<?php

use App\Models\Navigation;

function companyName()
{
    return "TravHub Global Limited";
}

function getNavigations()
{
    $query = Navigation::query()
        ->whereNull('parent_id')
        ->where('status', 'active');

    $navigations = $query->with(['children' => function ($q) {
        $q->where('status', 'active');
    }])->get();

    return $navigations;
    // return [];
}

if (!function_exists('format_mmddyyyy')) {
    function format_mmddyyyy($date) {
        return date('m-d-Y', strtotime($date));
    }
}
