<?php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityCategories = ActivityCategory::orderBy('id', 'asc')->paginate(10);
        return view('backend.activityCategories.index', compact('activityCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.activityCategories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        try {
            $activityCategory = ActivityCategory::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('activity-categories.index')->with('success', 'ActivityCategory created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($activityCategory)
    {
        $activityCategory = ActivityCategory::where('uuid', $activityCategory)->withCount('activities')->first();
        return view('backend.activityCategories.show', compact('activityCategory'));
    }

    public function edit($activityCategory)
    {
        $activityCategory = ActivityCategory::where('uuid', $activityCategory)->first();
        return view('backend.activityCategories.edit', compact('activityCategory'));
    }

    public function update(Request $request, ActivityCategory $activityCategory)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'required|boolean',
        ]);

        try {
            $activityCategory->update([
                'title' => $request->title,
                'updated_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('activity-categories.index')->with('success', 'ActivityCategory updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $activityCategory = ActivityCategory::where('uuid', $uuid);
        $activityCategory->delete(); // this is soft delete

        return redirect()->route('activity-categories.index')->with('success', 'ActivityCategory moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = ActivityCategory::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.activityCategories.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $activityCategory = ActivityCategory::onlyTrashed()->where('uuid', $uuid);
        $activityCategory->restore();

        return redirect()->route('activity-categories.trash')->with('success', 'ActivityCategory restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $activityCategory = ActivityCategory::onlyTrashed()->where('uuid', $uuid);
        $activityCategory->forceDelete();

        return redirect()->route('activity-categories.trash')->with('success', 'ActivityCategory permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = ActivityCategory::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $activityCategories = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($activityCategories);
        } catch (\Throwable $e) {
            \Log::error('Activity Categories getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
