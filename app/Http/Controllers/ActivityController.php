<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
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
        $countries = Country::get();
        $cities = City::get();
        $currencies = Currency::get();
        return view('backend.activities.create', compact('countries', 'cities', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'country_uuid' => 'required|exists:countries,uuid',
            'city_uuid' => 'required|exists:cities,uuid',
            'currency_uuid' => 'nullable|exists:currencies,uuid',
            'description' => 'nullable|string',
            'price_json' => 'nullable|string'
        ]);

        try {
            $country = Country::where('uuid', $request->country_uuid)->firstOrFail();
            $city = City::where('uuid', $request->city_uuid)->firstOrFail();
            $currency = $request->currency_uuid
                ? Currency::where('uuid', $request->currency_uuid)->first()
                : null;

            $prices = json_decode($request->price_json, true) ?? [];

            $adult = $prices['adult'] ?? 0;
            $child = $prices['child'] ?? 0;
            $infant = $prices['infant'] ?? 0;

            $priceData = [
                'adult' => (float) $adult,
                'child' => (float) $child,
                'infant' => (float) $infant,
            ];

            $activity = Activity::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $validated['title'],
                'description' => $request->description,
                'country_id' => $country->id,
                'country_uuid' => $country->uuid,
                'country_title' => $country->title,
                'city_id' => $city->id,
                'city_uuid' => $city->uuid,
                'city_title' => $city->title,
                'currency_id' => $currency?->id,
                'currency_uuid' => $currency?->uuid,
                'currency_title' => $currency?->title,
                'price' => json_encode($priceData),
                'created_by' => Auth::id(),
            ]);

            return redirect()
                ->route('activities.index')
                ->with('success', 'Activity created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show($activity)
    // {
    //     try {
    //         $activity = Activity::where('uuid', $activity)->withCount('inclusions')->first();
    //         return view('backend.activities.show', compact('activity'));
    //     } catch (\Throwable $th) {
    //         return redirect()->route('activities.index')->with('error', 'Activity not found.', 404);
    //     }
    // }

    public function show($uuid)
    {
        $activity = Activity::withCount('inclusions')
            ->with(['country', 'city', 'createdBy', 'updatedBy'])
            ->where('uuid', $uuid)
            ->firstOrFail(); // throws ModelNotFoundException if not found

        return view('backend.activities.show', compact('activity'));
    }

    // Edit Activity
    public function edit($uuid)
    {
        $countries = Country::get();
        $cities = City::get();
        $currencies = Currency::get();
        $activity = Activity::where('uuid', $uuid)->firstOrFail();
        return view('backend.activities.edit', compact('activity', 'countries', 'cities', 'currencies'));
    }

    // Update Activity
    public function update(Request $request, $uuid)
    {
        $activity = Activity::where('uuid', $uuid)->firstOrFail();

        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'country_uuid' => 'required|exists:countries,uuid',
            'city_uuid' => 'required|exists:cities,uuid',
            'currency_uuid' => 'nullable|exists:currencies,uuid',
            'description' => 'nullable|string',
            'price_json' => 'nullable|string'
        ]);

        try {
            // Resolve foreign models
            $country = Country::where('uuid', $request->country_uuid)->firstOrFail();
            $city = City::where('uuid', $request->city_uuid)->firstOrFail();
            $currency = $request->currency_uuid
                ? Currency::where('uuid', $request->currency_uuid)->first()
                : null;

            // Parse prices safely
            $prices = json_decode($request->price_json, true) ?? [];
            $adult = $prices['adult'] ?? 0;
            $child = $prices['child'] ?? 0;
            $infant = $prices['infant'] ?? 0;

            $priceData = [
                'adult' => (float) $adult,
                'child' => (float) $child,
                'infant' => (float) $infant,
            ];

            // Update activity
            $activity->update([
                'title' => $request->title,
                'description' => $request->description,
                'country_id' => $country->id,
                'country_uuid' => $country->uuid,
                'country_title' => $country->title,
                'city_id' => $city->id,
                'city_uuid' => $city->uuid,
                'city_title' => $city->title,
                'currency_uuid' => $currency?->uuid,
                'currency_title' => $currency?->title,
                'price' => json_encode($priceData),
                'created_by' => Auth::id(),
                'status' => $request->input('status') == '1' ? 'active' : 'inactive',
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
            $query = Activity::with('createdBy', 'country', 'city', 'currency');

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
