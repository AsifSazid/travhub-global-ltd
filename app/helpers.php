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
        ->where('is_active', true);

    $navigations = $query->with(['children' => function ($q) {
        $q->where('is_active', true);
    }])->get();

    dd($navigations);
}
