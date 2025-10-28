<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::orderBy('id', 'asc')->paginate(10);
        return view('backend.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activityCategories = ActivityCategory::get();
        return view('backend.activities.create', compact('activityCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'activity_category_uuid' => 'required'
        ]);

        
        $activityCategory = ActivityCategory::where('uuid', $request->activity_category_uuid)->first();
        
        // dd($activityCategory);

        try {
            $activity = Activity::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'activity_category_uuid' => $request->activity_category_uuid,
                'activity_category_id' => $activityCategory->id,
                'activity_category_title' => $activityCategory->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('activities.index')->with('success', 'Activity created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($activity)
    {
        $activity = Activity::where('uuid', $activity)->withCount('inclusions')->first();
        return view('backend.activities.show', compact('activity'));
    }

    public function edit($activity)
    {
        $activity = Activity::where('uuid', $activity)->first();
        $countries = Country::get();

        return view('backend.activities.edit', compact('activity','countries'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'title' => 'required|string',
            'country_uuid' => 'required',
        ]);

        $country_data = Country::where('uuid', $request->country_uuid)->first();

        try {
            $activity->update([
                'title' => $request->title,
                'country_uuid' => $request->country_uuid,
                'country_id' => $country_data->id,
                'country_title' => $country_data->title,
                'created_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('activities.index')->with('success', 'Activity updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $activity = Activity::where('uuid', $uuid);
        $activity->delete(); // this is soft delete

        return redirect()->route('activities.index')->with('success', 'Activity moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = Activity::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.activities.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $activity = Activity::onlyTrashed()->where('uuid', $uuid);
        $activity->restore();

        return redirect()->route('activities.trash')->with('success', 'Activity restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $activity = Activity::onlyTrashed()->where('uuid', $uuid);
        $activity->forceDelete();

        return redirect()->route('activities.trash')->with('success', 'Activity permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = Activity::with('activityCategory');

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $activities = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($activities);
        } catch (\Throwable $e) {
            \Log::error('Activities getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = Activity::with('user');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $activities = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>" . companyName() . "</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.activities.pdf', compact('activities'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
}
