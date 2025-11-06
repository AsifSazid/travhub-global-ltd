<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
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
    Hotel,
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

    public function edit()
    {
        dd('edit');
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
        // eager load cities
        $countries = Country::with(['cities' => function ($q) {
            $q->where('status', 'active')->orderBy('title');
        }])->where('status', 'active')->get();

        $activities = Activity::where('status', 'active')->get();

        $packDesInfo = PackDestinationInfo::where('package_uuid', $uuid)->first();

        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 1;

        return view('backend.packages.create-multistep', [
            'uuid' => $uuid,
            'step' => $step,
            'title' => "Destination Information",
            'countries' => $countries,
            'activities' => $activities,
            'packDesInfo' => $packDesInfo ?? null,
            'completedStep' => $completedStep
        ]);
    }

    public function stepTwo($uuid, $step)
    {
        $packQuatInfo = PackQuatDetail::where('package_uuid', $uuid)->first();

        if ($packQuatInfo && $packQuatInfo->no_of_pax) {
            $packQuatInfo->no_of_pax = json_decode($packQuatInfo->no_of_pax, true);
        }

        $title = "Quatation Information";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 2;

        return view('backend.packages.create-multistep', [
            'uuid' => $uuid,
            'step' => $step,
            'title' => $title,
            'packQuatInfo' => $packQuatInfo ?? null,
            'completedStep' => $completedStep
        ]);
    }

    public function stepThree($uuid, $step)
    {
        $packDestinationCities = PackDestinationInfo::where('package_uuid', $uuid)->first();

        $cityIds = $packDestinationCities?->cities;

        if (is_string($cityIds)) {
            $decoded = json_decode($cityIds, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $cityIds = $decoded;
        }

        $cityIds = Arr::flatten($cityIds);

        if (is_array($cityIds) && count($cityIds)) {
            $cities = City::whereIn('id', $cityIds)
                ->get(['id', 'uuid', 'title'])
                ->map(function ($city) use ($packDestinationCities) {
                    $city->country_id = $packDestinationCities->country_id;
                    return $city;
                });
        } else {
            $cities = collect();
        }

        $title = "Accommodation Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 3;

        return view('backend.packages.create-multistep', compact('uuid', 'step', 'cities', 'title', 'completedStep'));
    }


    public function stepFour($uuid, $step)
    {
        $currencies = Currency::where('status', 'active')->get();
        $pkgPrice = PackPrice::where('package_uuid', $uuid)->first();
        $packAccomoDetails = PackAccomoDetail::where('package_uuid', $uuid)->first();
        $hotels = json_decode($packAccomoDetails->hotels) ?? [];
        foreach ($hotels as $hotel) {
            $city = City::find($hotel->city_id);
            if ($city) {
                $hotel->city_title = $city->title;
            } else {
                $hotel->city_title = 'City Not Found';
            }
        }
        // dd($hotels);
        $title = "Pricing Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 4;
        return view('backend.packages.create-multistep', compact('uuid', 'step', 'pkgPrice', 'currencies', 'hotels', 'title', 'completedStep'));
    }

    public function stepFive($uuid, $step)
    {
        $packDesDetails = PackDestinationInfo::where('package_uuid', $uuid)->first();
        $cities = json_decode($packDesDetails->cities) ?? [];
        if (is_string($cities)) {
            $decoded = json_decode($cities, true);
            if (is_string($decoded)) {
                $cities = json_decode($decoded, true);
            } else {
                $cities = $decoded;
            }
        }
        $activities = json_decode($packDesDetails->activities, true) ?? [];

        // Handle double JSON encoding cases
        if (is_string($activities)) {
            $activities = json_decode($activities, true) ?? [];
        }

        // Extract all activity IDs at once
        $activityIds = collect($activities)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Fetch all activity details in one query
        $activitiesWithDetails = Activity::whereIn('id', $activityIds)
            ->select('id', 'description')
            ->get()
            ->keyBy('id');

        // Merge descriptions back into the original structure
        $activities = collect($activities)->map(function ($item) use ($activitiesWithDetails) {
            if (isset($item['id']) && $activitiesWithDetails->has($item['id'])) {
                $item['description'] = $activitiesWithDetails[$item['id']]->description;
            }
            return $item;
        })->toArray();

        // dd($activities);

        $packQuatDetails = PackQuatDetail::where('package_uuid', $uuid)->first();
        $title = "Itinerary Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 5;
        // dd($packQuatDetails, $package);
        return view('backend.packages.create-multistep', compact('uuid', 'step', 'activities', 'cities', 'packQuatDetails', 'title', 'completedStep'));
    }

    public function stepSix($uuid, $step)
    {
        // dd($uuid, $step);
        $activities = Activity::where('activity_category_id', 3)->with('inclusions')->get();
        $title = "Inclusion Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 6;
        return view('backend.packages.create-multistep', compact('uuid', 'step', 'activities', 'title', 'completedStep'));
    }

    protected function stepSeven($uuid, $step)
    {
        $pkg = Package::where('uuid', $uuid)->firstOrFail();
        $pkgDesInfo = PackDestinationInfo::where('package_uuid', $uuid)->firstOrFail();
        $pkgQuatDetail = PackQuatDetail::where('package_uuid', $uuid)->firstOrFail();
        $pkgAccomoDetail = PackAccomoDetail::where('package_uuid', $uuid)->firstOrFail();
        $pkgPrice = PackPrice::where('package_uuid', $uuid)->firstOrFail();
        $pkgItenaries = PackItenaries::where('package_uuid', $uuid)->firstOrFail();
        $pkgInclusions =  PackInclusion::where('package_uuid', $uuid)->get();

        // dd($pkg, $pkgDesInfo, $pkgQuatDetail, $pkgAccomoDetail, $pkgPrice, $pkgItenaries, $pkgInclusions);
        $title = "Itinerary Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 7;

        return view('backend.packages.pkg-details', [
            'package' => $pkg,
            'pkgDesInfo' => $pkgDesInfo,
            'pkgQuatDetail' => $pkgQuatDetail,
            'pkgAccomoDetail' => $pkgAccomoDetail,
            'pkgPrice' => $pkgPrice,
            'pkgItenaries' => $pkgItenaries,
            'pkgInclusions' => $pkgInclusions,
            'title' => $title,
            'completedStep' => $completedStep
        ])->with('success', 'Package completed successfully.');
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
                'activities' => 'required',
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
                    'cities' => json_encode($validated['cities']),
                    'activities' => json_encode($validated['activities']),
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
                    'no_of_pax' => json_encode($validated['no_of_pax']) ?? null,
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
            $selectedHotels = $request->input('hotels', []);
            $customHotels = $request->input('custom_hotels', []);
            $allHotels = [];

            // Existing Hotels
            foreach ($selectedHotels as $cityId => $hotelsInCity) {
                foreach ($hotelsInCity as $hotelId => $hotelData) {
                    if (!empty($hotelData['id'])) {
                        $allHotels[] = [
                            'id' => (string) $hotelData['id'],
                            'title' => $hotelData['title'] ?? null,
                            'city_id' => (int) $cityId,
                            'type' => 'existing',
                        ];
                    }
                }
            }

            // Custom Hotels
            foreach ($customHotels as $cityId => $titles) {
                foreach ($titles as $title) {
                    if (!empty($title)) {
                        $newHotel = Hotel::create([
                            'uuid'    => Str::uuid(),
                            'city_id' => $cityId,
                            'title'   => trim($title),
                        ]);

                        $allHotels[] = [
                            'id' => (string)$newHotel->id,
                            'title' => $newHotel->title,
                            'city_id' => (int)$cityId,
                            'type' => 'custom',
                        ];
                    }
                }
            }

            $pkg = Package::where('uuid', $uuid)->firstOrFail();
            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            PackAccomoDetail::updateOrCreate(
                ['package_uuid' => $pkg->uuid],
                [
                    'uuid'           => Str::uuid(),
                    'title'          => $formatted_title,
                    'package_id'     => $pkg->id,
                    'package_uuid'   => $pkg->uuid,
                    'package_title'  => $pkg->title,
                    'hotels'         => json_encode($allHotels, JSON_PRETTY_PRINT),
                    'status'         => 'active',
                    'created_by'     => Auth::id(),
                ]
            );

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('packages.step', [
                'uuid' => $uuid,
                'step' => $step + 1
            ])->with('success', 'Step ' . $step . ' saved successfully.');
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getLine());
        }
    }

    public function stepFourStore($request, $uuid, $step)
    {
        try {
            // dd($request->all());
            $validated = $request->validate([
                'currency_id' => 'required|exists:currencies,id',
                'air_ticket_details' => 'nullable|string',
                'format_data' => 'nullable|string'
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
                    'pack_price' => json_encode($validated['format_data']),
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

    public function packagePdf()
    {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'></div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.packages.full-package-pdf');
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }

    public function getPackageInfo($uuid)
    {
        $package = Package::where('uuid', $uuid)->firstOrFail();
        return $package;
    }
}
