<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\{
    Activity,
    Package,
    PackDestinationInfo,
    PackQuatDetail,
    PackAccomoDetail,
    PackPrice,
    PackItenaries,
    PackInclusion,
    Country,
    ActivityCategory,
    City,
    Currency,
    Inclusion
};

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('id', 'asc')->paginate(10);
        return view('backend.packages.index', compact('packages'));
    }

    public function getData(Request $request)
    {
        try {
            $query = Package::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $packages = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($packages);
        } catch (\Throwable $e) {
            \Log::error('Countrise getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function create()
    {
        return view('backend.packages.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
            ]);

            $pkg = Package::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('packages.step', ['uuid' => $pkg->uuid, 'step' => 1])->with('success', 'Package creatation started successfully!');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function step($uuid, $step)
    {
        // dd($uuid, $step);
        switch ((int)$step) {
            case 1:
                return $this->stepOne($uuid, $step);
            case 2:
                return $this->stepTwo($uuid, $step);
            case 3:
                return $this->stepThree($uuid, $step);
            case 4:
                return $this->stepFour($uuid, $step);
            case 5:
                return $this->stepFive($uuid, $step);
            case 6:
                return $this->stepSix($uuid, $step);
            case 7:
                return $this->stepSeven($uuid, $step);
            default:
                return redirect()->back()->withErrors(['Invalid step']);
        }
    }

    public function stepOne($uuid, $step)
    {
        $countries = Country::where('status', 'active')->get();
        $activities = Activity::where('status', 'active')->where('activity_category_id', 1)->get();

        return view('backend.packages.create-multistep', compact('uuid', 'step', 'countries', 'activities'));
    }

    public function stepTwo($uuid, $step)
    {
        // dd($uuid, $step);
        return view('backend.packages.create-multistep', compact('uuid', 'step'));
    }

    public function stepThree($uuid, $step)
    {
        $packDestinationCities = PackDestinationInfo::where('package_uuid', $uuid)->first();

        $cityIds = $packDestinationCities?->cities;

        if (is_string($cityIds)) {
            $decoded = json_decode($cityIds, true);
            // If still string after decode, decode again
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $cityIds = $decoded;
        }

        if (is_array($cityIds) && count($cityIds)) {
            $cities = City::whereIn('id', $cityIds)->get(['id', 'uuid', 'title']);
        } else {
            $cities = collect();
        }

        return view('backend.packages.create-multistep', compact('uuid', 'step', 'cities'));
    }

    public function stepFour($uuid, $step)
    {
        $currencies = Currency::where('status', 'active')->get();
        $packQuatDetails = PackQuatDetail::where('package_uuid', $uuid)->first();
        return view('backend.packages.create-multistep', compact('uuid', 'step', 'currencies', 'packQuatDetails'));
    }

    public function stepFive($uuid, $step)
    {
        $activites = Activity::where('activity_category_id', 2)->get();
        $packQuatDetails = PackQuatDetail::where('package_uuid', $uuid)->first();
        return view('backend.packages.create-multistep', compact('uuid', 'step', 'activites', 'packQuatDetails'));
    }

    public function stepSix($uuid, $step)
    {
        // dd($uuid, $step);
        $activities = Activity::where('activity_category_id', 3)->with('inclusions')->get();
        return view('backend.packages.create-multistep', compact('uuid', 'step', 'activities'));
    }

    protected function stepSeven($uuid, $step)
    {
        dd($uuid, $step);
        $request->validate([
            'uuid' => 'required|exists:packages,uuid'
        ]);

        $pkg = Package::where('uuid', $request->uuid)->firstOrFail();
        $pkg->update([
            'is_complete' => true,
            'completion_status' => 'completed',
            'progress_step' => 7,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('packages.index')->with('success', 'Package completed successfully.');
    }

    public function stepForStore(Request $request, $uuid, $step)
    {
        // dd('asche', $uuid, $step, $request->all());
        switch ((int)$step) {
            case 1:
                return $this->stepOneStore($request, $uuid, $step);
            case 2:
                return $this->stepTwoStore($request, $uuid, $step);
            case 3:
                return $this->stepThreeStore($request, $uuid, $step);
            case 4:
                return $this->stepFourStore($request, $uuid, $step);
            case 5:
                return $this->stepFiveStore($request, $uuid, $step);
            case 6:
                return $this->stepSixStore($request, $uuid, $step);
            case 7:
                return $this->stepSeven($uuid, $step);
            default:
                return redirect()->back()->withErrors(['Invalid step']);
        }
    }

    public function stepOneStore($request, $uuid, $step)
    {
        try {
            $validated  = $request->validate([
                'country_id' => 'required|exists:countries,id',
                'activity_id' => 'required|exists:activities,id',
                'cities' => 'required'
            ]);

            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            $pkgDesInfo = PackDestinationInfo::updateOrCreate(
                ['package_id' => $pkg->id],
                [
                    'uuid' => Str::uuid(),
                    'title' => $formatted_title,
                    'package_id' => $pkg->id,
                    'package_uuid' => $pkg->uuid,
                    'package_title' => $pkg->title,
                    'country_id' => $validated['country_id'],
                    'country_uuid' => optional(Country::find($validated['country_id']))->uuid,
                    'country_title' => optional(Country::find($validated['country_id']))->title,
                    'activity_id' => $validated['activity_id'],
                    'activity_uuid' => optional(Activity::find($validated['activity_id']))->uuid,
                    'activity_title' => optional(Activity::find($validated['activity_id']))->title,
                    'cities' => json_encode($validated['cities']),
                    'status' => 'active',
                    'created_by' => Auth::id()
                ]
            );

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('packages.step', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function stepTwoStore($request, $uuid, $step)
    {
        try {
            $validated  = $request->validate([
                'duration' => 'nullable|integer|min:1',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'no_of_pax' => 'nullable|string'
            ]);

            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            PackQuatDetail::updateOrCreate(
                ['package_uuid' => $pkg->uuid],
                [
                    'uuid' => Str::uuid(),
                    'title' => $formatted_title,
                    'package_id' => $pkg->id,
                    'package_uuid' => $pkg->uuid,
                    'package_title' => $pkg->title,
                    'duration' => $validated['duration'] ?? null,
                    'start_date' => $validated['start_date'] ?? null,
                    'end_date' => $validated['end_date'] ?? null,
                    'no_of_pax' => $validated['no_of_pax'] ?? null,
                    'status' => 'active',
                    'created_by' => Auth::id()
                ]
            );

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('packages.step', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function stepThreeStore($request, $uuid, $step)
    {
        try {
            // dd($request->all());
            $validated = $request->validate([
                'accomo_cities' => 'nullable|string',
                'hotels' => 'required'
            ]);

            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            $packAccomo = PackAccomoDetail::updateOrCreate(
                ['package_uuid' => $pkg->uuid],
                [
                    'uuid' => Str::uuid(),
                    'title' => $formatted_title,
                    'package_id' => $pkg->id,
                    'package_uuid' => $pkg->uuid,
                    'package_title' => $pkg->title,
                    'hotels' => json_encode($validated['hotels']) ?? null,
                    'status' => 'active',
                    'created_by' => Auth::id()
                ]
            );

            $pkg->update(['progress_step' => $step]);

            // dd($packAccomo, $pkg);

            return redirect()->route('packages.step', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function stepFourStore($request, $uuid, $step)
    {
        try {
            // dd($request->all());
            $validated = $request->validate([
                'currency_id' => 'required|exists:currencies,id',
                'air_ticket_details' => 'nullable|string',
                'price_options' => 'nullable|string'
            ]);

            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            $packPrice = PackPrice::updateOrCreate(
                ['package_uuid' => $pkg->uuid],
                [
                    'uuid' => Str::uuid(),
                    'title' => $formatted_title,
                    'package_id' => $pkg->id,
                    'package_uuid' => $pkg->uuid,
                    'package_title' => $pkg->title,
                    'currency_id' => $validated['currency_id'] ?? null,
                    'currency_uuid' => optional(Currency::find($validated['currency_id']))->uuid,
                    'currency_title' => optional(Currency::find($validated['currency_id']))->title,
                    'air_ticket_details' => json_encode($validated['air_ticket_details']) ?? null,
                    'pack_price' => json_encode($validated['price_options']),
                    'status' => 'active',
                    'created_by' => Auth::id()
                ]
            );

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('packages.step', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function stepFiveStore($request, $uuid, $step)
    {
        try {
            // dd($request->input('itenary'));
            $itenaries = json_decode($request->input('itenary'), true);

            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            foreach ($itenaries as $item) {
                PackItenaries::create([
                    'uuid' => Str::uuid(),
                    'title' => $item['title'] ?? null,
                    'description' => $item['title'] ?? null, // চাইলে অন্য description ফিল্ড দিতে পারো
                    'status' => 'active',
                    'icon' => null,

                    // Foreign key info
                    'package_id' => $pkg['id'] ?? null,
                    'package_uuid' => $pkg['uuid'] ?? null,
                    'package_title' => $pkg['title'] ?? null,

                    // JSON data
                    'cities' => json_encode([$item['overnightStay'] ?? null]), // overnightStay কে cities হিসেবে রাখছি
                    'activities' => json_encode($item['activities'] ?? []),

                    // Meals array থেকে প্রথম meal বা join করে string বানানো
                    'meal' => isset($item['meals']) ? strtolower($item['meals'][0]) : null,

                    // Extra info
                    'date' => $item['date'] ?? null,
                    'day_number' => $item['dayNumber'] ?? null,

                    'created_by' => Auth::id(),
                ]);
            }

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('packages.step', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function stepSixStore($request, $uuid, $step)
    {
        try {
            // dd($request->all());
            $pkg = Package::where('uuid', $uuid)->firstOrFail();
            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            // Existing inclusions
            $selectedInclusions = $request->input('inclusions', []);

            // Custom inclusions
            $customInclusions = $request->input('custom_inclusions', []);

            // Example: Save to DB
            foreach ($selectedInclusions as $activityId => $inclusionIds) {
                foreach ($inclusionIds as $id) {
                    PackInclusion::create([
                        'uuid' => Str::uuid(),
                        'title' => $formatted_title,
                        'package_uuid' => $uuid,
                        'activity_id'  => $activityId,
                        'inclusion_id' => $id,
                        'package_id' => $pkg['id'] ?? null,
                        'package_title' => $pkg['title'] ?? null,

                    ]);
                }
            }

            // Handle custom inclusions (new user-defined texts)
            foreach ($customInclusions as $activityId => $titles) {
                foreach ($titles as $title) {
                    $new = Inclusion::create([
                        'uuid' => Str::uuid(),
                        'activity_id' => $activityId,
                        'title'       => $title,
                    ]);

                    PackInclusion::create([
                        'uuid' => Str::uuid(),
                        'title' => $formatted_title,
                        'package_uuid' => $uuid,
                        'activity_id'  => $activityId,
                        'inclusion_id' => $new->id,
                        'package_id' => $pkg['id'] ?? null,
                        'package_title' => $pkg['title'] ?? null,
                    ]);
                }
            }

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('packages.step', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}
