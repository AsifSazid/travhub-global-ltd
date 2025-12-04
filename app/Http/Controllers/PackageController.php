<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage; // <- Make sure this is at the top
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

    public function edit($uuid)
    {
        $pkg = Package::where('uuid', $uuid)->first();
        // dd($pkg);
        return view('backend.packages.edit', ([
            'package' => $pkg
        ]));
    }

    public function store(Request $request)
    {
        try {
            // dd($request->all());
            // Validation
            $request->validate([
                'title' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'description' => 'nullable',
                'rating' => 'required'
            ]);

            // Package creation
            $pkg = Package::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'description' => $request->description,
                'rating' => $request->rating,
                'created_by' => Auth::user()->id,
            ]);

            // Image Upload & polymorphic save
            if ($request->hasFile('image')) {
                $file_name = $this->uploadFile($request->file('image'), $pkg->id);

                // Save to images table
                $pkg->images()->create([
                    'url' => $file_name
                ]);
            }

            return redirect()->route('backend.packages.step.show', ['uuid' => $pkg->uuid, 'step' => 1])
                ->with('success', 'Package creation started successfully!');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function update(Request $request, $uuid)
    {
        try {
            // Validation
            $request->validate([
                'title' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'description' => 'nullable',
                'rating' => 'required'
            ]);

            // Package find
            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            // Update title
            $pkg->update([
                'title' => $request->title,
                'description' => $request->description,
                'rating' => $request->rating,
            ]);

            // If new image uploaded
            if ($request->hasFile('image')) {

                // DELETE old image (file + db record)
                if ($pkg->images()->exists()) {

                    $oldImage = $pkg->images()->first();

                    $oldPath = storage_path("app/public/images/packages/" . $oldImage->url);

                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }

                    $oldImage->delete();
                }

                // Upload new image
                $file_name = $this->uploadFile($request->file('image'), $pkg->id);

                // Save new to DB
                $pkg->images()->create([
                    'url' => $file_name,
                ]);
            }

            return redirect()->route('backend.packages.index')
                ->with('success', 'Package updated successfully!');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    // local
    private function uploadFile($file, $name)
    {
        $folder = storage_path('app/public/images/packages');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }

        $timestamp = str_replace([' ', ':', '-'], '', now());
        $file_name = $timestamp . '_' . $name . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $file_name);

        return $file_name;
    }

    // cPanel
    // private function uploadFile($file, $name)
    // {
    // $timestamp = str_replace([' ', ':', '-'], '', now());
    // $file_name = $timestamp . '_' . $name . '.' . $file->getClientOriginalExtension();

    // // Save to 'public' disk defined in filesystems.php
    // $path = $file->storeAs('images/packages', $file_name, 'public');

    // return $file_name; // just return file name, path is images/packages/...

    // }

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

        if ($packDesInfo) {
            $packDesInfo->cities = json_decode(json_decode($packDesInfo->cities, true), true) ?? [];
            $packDesInfo->activities = json_decode(json_decode($packDesInfo->activities, true), true) ?? [];
        }

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

            // First decode wrapper string
            $firstDecode = json_decode($packQuatInfo->no_of_pax, true);

            // If still string → decode again
            if (is_string($firstDecode)) {
                $packQuatInfo->no_of_pax = json_decode($firstDecode, true);
            } else {
                $packQuatInfo->no_of_pax = $firstDecode;
            }
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

        /* Already existing work (unchanged) */
        $cityIds = $packDestinationCities?->cities;
        if (is_string($cityIds)) {
            $decoded = json_decode($cityIds, true);
            if (is_string($decoded)) $decoded = json_decode($decoded, true);
            $cityIds = $decoded;
        }
        $cityIds = Arr::flatten($cityIds);

        if (is_array($cityIds) && count($cityIds)) {
            $cities = City::whereIn('id', $cityIds)->get(['id', 'uuid', 'title'])
                ->map(function ($city) use ($packDestinationCities) {
                    $city->country_id = $packDestinationCities->country_id;
                    return $city;
                });
        } else {
            $cities = collect();
        }

        /* NEW PART → old saved accommodation */
        $accomo = PackAccomoDetail::where('package_uuid', $uuid)->first();

        $savedHotels = [];
        if ($accomo && $accomo->hotels) {
            $savedHotels = json_decode($accomo->hotels, true);
            if (!is_array($savedHotels)) $savedHotels = [];
        }

        $title = "Accommodation Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 3;

        return view(
            'backend.packages.create-multistep',
            compact('uuid', 'step', 'cities', 'savedHotels', 'title', 'completedStep')
        );
    }

    public function stepFour($uuid, $step)
    {
        $currencies = Currency::where('status', 'active')->get();

        $pkgPrice = PackPrice::where('package_uuid', $uuid)->first();

        // Now $pkgPrice->pack_price is already an array
        $formatData = $pkgPrice->pack_price ?? [];

        // Accommodation
        $packAccomoDetails = PackAccomoDetail::where('package_uuid', $uuid)->first();
        $hotels = json_decode($packAccomoDetails->hotels) ?? [];

        // Add city title
        foreach ($hotels as &$hotel) {
            $city = City::find($hotel->city_id ?? null);
            $hotel->city_title = $city ? $city->title : 'City Not Found';
        }

        $title = "Pricing Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 4;


        return view('backend.packages.create-multistep', [
            'uuid' => $uuid,
            'step' => $step,
            'pkgPrice' => $pkgPrice,
            'currencies' => $currencies,
            'hotels' => $hotels,
            'title' => $title,
            'completedStep' => $completedStep,
            'formatData' => $formatData, // ✅ already array
        ]);
    }

    public function stepFive($uuid, $step)
    {
        $packDesDetails = PackDestinationInfo::where('package_uuid', $uuid)->first();
        $cities = $packDesDetails->cities ? json_decode($packDesDetails->cities, true) : [];
        if (is_string($cities)) {
            $decoded = json_decode($cities, true);
            if (is_string($decoded)) {
                $cities = json_decode($decoded, true);
            } else {
                $cities = $decoded;
            }
        }
        $activities = $packDesDetails->activities ? json_decode($packDesDetails->activities, true) : [];
        if (is_string($activities)) {
            $activities = json_decode($activities, true) ?? [];
        }

        $staticActivities = [
            [
                'id' => null,
                'title' => 'Airport Pick',
                'description' => '',
                'Airport Name' => '',
                'Terminal/Gate' => '',
                'Drop off location' => '',
                'Pickup Time' => '',
                'Vehicles Use' => '',
                'Flight No' => ''
            ],
            [
                'id' => null,
                'title' => 'Domestic Connecting Flight',
                'description' => '',
                'Self Transfer' => '',
                'Specific Instructions' => '',
                'Add next flight details' =>   ''
            ],
            [
                'id' => null,
                'title' => 'Car Transfer',
                'description' => '',
                'Start' => '',
                'End' => '',
                'Enroute activities' => '',
                'Vehicles Use' => ''
            ],
            [
                'id' => null,
                'title' => 'Airport Drop',
                'description' => '',
                'Hotel/Location' => '',
                'Airport' => '',
                'Pickup time' => '',
                'Vehicles Use' => ''
            ],
        ];

        $activityIds = collect($activities)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $activitiesWithDetails = Activity::whereIn('id', $activityIds)
            ->select('id', 'description')
            ->get()
            ->keyBy('id');

        $activities = collect($activities)->map(function ($item) use ($activitiesWithDetails) {
            if (isset($item['id']) && $activitiesWithDetails->has($item['id'])) {
                $item['description'] = $activitiesWithDetails[$item['id']]->description;
            }
            return $item;
        })->toArray();

        $activities = array_merge($staticActivities, $activities);

        $packQuatDetails = PackQuatDetail::where('package_uuid', $uuid)->first();
        $title = "Itinerary Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 5;
        $savedRows = PackItenaries::where('package_uuid', $uuid)
            ->orderBy('day_number')
            ->get();

        $savedItineraryDays = [];

        foreach ($savedRows as $row) {
            // activities
            $savedActivities = json_decode($row->activities, true) ?? [];
            if (is_string($savedActivities)) {
                $savedActivities = json_decode($savedActivities, true) ?? [];
            }

            // meals
            $savedMeals = [];
            if ($row->meals) {
                $decodedMeals = @json_decode($row->meals, true);
                if (is_array($decodedMeals)) {
                    $savedMeals = $decodedMeals;
                } else {
                    // maybe CSV string
                    $savedMeals = array_filter(array_map('trim', explode(',', $row->meals)));
                }
            }

            // cities
            $savedCities = [];
            if ($row->cities) {
                $decodedCities = @json_decode($row->cities, true);
                if (is_array($decodedCities)) {
                    $savedCities = $decodedCities;
                } else {
                    $savedCities = [$row->cities];
                }
            }

            $savedItineraryDays[] = [
                "id" => $row->id,
                "dayNumber" => (int)($row->day_number ?? 1),
                "title" => $row->title ?? ("Day " . ($row->day_number ?? 1)),
                "date" => $row->date ?? ($packQuatDetails->start_date ?? null),
                "overnightStay" => $savedCities[0] ?? ($cities[0]['id'] ?? null),
                "meals" => $savedMeals,
                "activities" => $savedActivities
            ];
        }


        return view('backend.packages.create-multistep', compact(
            'uuid',
            'step',
            'activities',
            'cities',
            'packDesDetails',
            'packQuatDetails',
            'title',
            'completedStep',
            'savedItineraryDays'
        ));
    }


    public function stepSix($uuid, $step)
    {
        $inclusions = [
            [
                'title' => 'Transfers',
                'icons' => 'fa-solid fa-plane-arrival',
                'sub_title' => ['1' => 'Airport transfers on arrival and departure', '2' => 'All inter-city transfers'],
            ],
            [
                'title' => 'Accommodation',
                'icons' => 'fa-solid fa-hotel',
                'sub_title' => ['1' => '7 nights in selected hotels', '2' => 'Daily breakfast included', '3' => 'Room upgrades available'],
            ],
            [
                'title' => 'Meals',
                'icons' => 'fa-solid fa-utensils',
                'sub_title' => ['1' => 'Daily breakfast', '2' => '3 lunches as specified', '3' => '2 dinners as specified'],
            ],
            [
                'title' => 'Tours & Excursions',
                'icons' => 'fa-solid fa-map-marked-alt',
                'sub_title' => ['1' => 'All tours mentioned in itinerary', '2' => 'Entrance fees to attractions', '3' => 'English-speaking guides'],
            ],
            [
                'title' => 'Professional Services',
                'icons' => 'fa-solid fa-user-tie',
                'sub_title' => ['1' => 'Professional Services', '2' => 'Dedicated tour manager'],
            ],
        ];
        $title = "Inclusion Details";
        $package = $this->getPackageInfo($uuid);
        $savedInclusions = PackInclusion::where('package_uuid', $uuid)->first();
        $completedStep = $package->progress_step ?? 6;
        if ($savedInclusions && $savedInclusions->inclusions) {
            $saved = json_decode($savedInclusions->inclusions, true);

            // override default with saved values
            foreach ($saved as $i => $savedInc) {
                // যদি saved inclusion থাকে তাহলে default inclusion replace করবে।
                if (isset($inclusions[$i])) {

                    $inclusions[$i]['title'] = $savedInc['title'];
                    $inclusions[$i]['icons'] = $savedInc['icons'];

                    // system + custom সব sub_title inject করা
                    $inclusions[$i]['sub_title'] = $savedInc['sub_title'];
                }
            }
        }
        return view('backend.packages.create-multistep', compact('uuid', 'step', 'inclusions', 'title', 'completedStep', 'savedInclusions'));
    }

    protected function stepSeven($uuid, $step)
    {
        $pkg = Package::where('uuid', $uuid)->firstOrFail();
        $pkgDesInfo = PackDestinationInfo::where('package_uuid', $uuid)->firstOrFail();
        $pkgQuatDetail = PackQuatDetail::where('package_uuid', $uuid)->firstOrFail();
        $pkgAccomoDetail = PackAccomoDetail::where('package_uuid', $uuid)->firstOrFail();
        $pkgPrice = PackPrice::where('package_uuid', $uuid)->firstOrFail();
        $pkgItenaries = PackItenaries::where('package_uuid', $uuid)->get();
        $pkgInclusions =  PackInclusion::where('package_uuid', $uuid)->firstOrFail();

        // dd($pkg, $pkgDesInfo, $pkgQuatDetail, $pkgAccomoDetail, $pkgPrice, $pkgItenaries, $pkgInclusions);
        $title = "Final Look & Submit";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 7;

        return view('backend.packages.create-multistep', [
            'package' => $pkg,
            'packDestinationInfo' => $pkgDesInfo,
            'packQuatDetail' => $pkgQuatDetail,
            'packAccomoDetail' => $pkgAccomoDetail,
            'packPrice' => $pkgPrice,
            'packItenaries' => $pkgItenaries,
            'packInclusion' => $pkgInclusions,
            'title' => $title,
            'completedStep' => $completedStep,
            'step' => $step,
            'uuid' => $uuid
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
                return $this->stepSevenStore($request, $uuid, $step);
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

            return redirect()->route('backend.packages.step.show', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
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

            return redirect()->route('backend.packages.step.show', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
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

            return redirect()->route('backend.packages.step.show', [
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
            // ✔ Validation
            $validated = $request->validate([
                'currency_id'        => 'required|exists:currencies,id',
                'overall_price'        => 'required',
                'air_ticket_details' => 'nullable',   // no string forcing
                'format_data'        => 'nullable'    // no string forcing
            ]);

            // ✔ Fetch package
            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            // ✔ Generate formatted title
            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            // -----------------------------
            //  FIX: air_ticket_details
            // -----------------------------
            // air_ticket_details is a normal HTML string (not JSON)
            $airTicket = $validated['air_ticket_details'] ?? null;

            // -----------------------------
            //  FIX: format_data (JSON string)
            // -----------------------------
            $packPriceData = $validated['format_data'] ?? null;

            // if JSON string → decode
            if (is_string($packPriceData)) {
                $packPriceData = json_decode($packPriceData, true);
            }

            // ✔ Save or update PackPrice
            $packPrice = PackPrice::updateOrCreate(
                ['package_uuid' => $pkg->uuid],
                [
                    'uuid'           => Str::uuid(),
                    'title'          => $formatted_title,
                    'package_id'     => $pkg->id,
                    'package_uuid'   => $pkg->uuid,
                    'package_title'  => $pkg->title,
                    'overall_price'  => $request->overall_price,

                    'currency_id'    => $validated['currency_id'],
                    'currency_uuid'  => optional(Currency::find($validated['currency_id']))->uuid,
                    'currency_title' => optional(Currency::find($validated['currency_id']))->title,

                    // ✔ JSON columns accept arrays / strings correctly
                    'air_ticket_details' => $airTicket,
                    'pack_price'         => $packPriceData,

                    'status'      => 'active',
                    'created_by'  => Auth::id(),
                ]
            );

            // ✔ Update step
            $pkg->update(['progress_step' => $step]);

            return redirect()->route('backend.packages.step.show', [
                'uuid' => $uuid,
                'step' => $step + 1,
            ])->with('success', 'Step ' . $step . ' saved.');
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
                $input_meals_array = (isset($item['meals']) && is_array($item['meals']) && count($item['meals']) > 0)
                    ? $item['meals']
                    : [];

                $meal_string = null;

                if (!empty($input_meals_array)) {
                    // 1. অ্যারের প্রতিটি উপাদানকে ছোট হাতের করে তারপর প্রথম অক্ষর বড় করা (ucfirst)
                    //    যাতে ইনপুট যেমনই আসুক ("BREAKFAST" বা "breakfast"), আউটপুট "Breakfast" হয়।
                    $capitalized_meals = array_map(function ($meal) {
                        return ucfirst(strtolower($meal));
                    }, $input_meals_array);

                    // 2. অ্যারেটিকে কমা এবং স্পেস দিয়ে যুক্ত করে একটি স্ট্রিং-এ পরিণত করা
                    //    যেমন: "Breakfast, Lunch, Dinner, Snacks"
                    $meal_string = implode(', ', $capitalized_meals);
                }

                PackItenaries::updateOrCreate(
                    ['title' => $item['title'], 'package_id' => $pkg['id']],
                    [
                        'uuid' => Str::uuid(),
                        'title' => $item['title'] ?? null,
                        'description' => $item['description'] ?? null, // চাইলে অন্য description ফিল্ড দিতে পারো
                        'status' => 'active',
                        'icon' => null,

                        // Foreign key info
                        'package_id' => $pkg['id'] ?? null,
                        'package_uuid' => $pkg['uuid'] ?? null,
                        'package_title' => $pkg['title'] ?? null,

                        // JSON data
                        'cities' => json_encode([$item['overnightStay'] ?? null]), // overnightStay কে cities হিসেবে রাখছি
                        'activities' => json_encode($item['activities'] ?? []),

                        'meals' => $meal_string,

                        // Extra info
                        'date' => $item['date'] ?? null,
                        'day_number' => $item['dayNumber'] ?? null,

                        'created_by' => Auth::id(),
                    ]
                );
            }

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('backend.packages.step.show', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function stepSixStore($request, $uuid, $step)
    {
        try {
            $pkg = Package::where('uuid', $uuid)->firstOrFail();

            $formatted_title = str_replace(' ', '_', $pkg->title) . '+' . substr($uuid, -4);

            $inclusions = $request->input('inclusions', []);

            foreach ($inclusions as $catIndex => $category) {
                if (isset($category['sub_title']) && is_array($category['sub_title'])) {
                    $filteredSubs = [];
                    foreach ($category['sub_title'] as $sub) {
                        // Include only if selected exists or save all
                        if (!isset($sub['selected']) || $sub['selected'] == '1') {
                            $filteredSubs[] = $sub;
                        }
                    }
                    $inclusions[$catIndex]['sub_title'] = $filteredSubs;
                }
            }

            PackInclusion::updateOrCreate(
                ['package_uuid' => $uuid], // Update if already exists
                [
                    'uuid' => Str::uuid(),
                    'title' => $formatted_title,
                    'package_uuid' => $uuid,
                    'inclusions' => json_encode($inclusions, JSON_UNESCAPED_UNICODE),
                    'package_id' => $pkg->id,
                    'package_title' => $pkg->title,
                ]
            );

            $pkg->update(['progress_step' => $step]);

            return redirect()->route('backend.packages.step.show', ['uuid' => $uuid, 'step' => $step + 1])->with('success', 'Step ' . $step . ' saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function stepSevenStore($request, $uuid, $step)
    {
        try {
            $pkg = Package::where('uuid', $uuid)->firstOrFail();
            $pkg->update([
                'progress_step' => $step,
                'completion_status' => 'completed',
                'status' => 'active'
            ]);

            return redirect()->route('backend.packages.index')->with('success', 'Package completed successfully.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function show($uuid)
    {
        $pkg = Package::where('uuid', $uuid)->firstOrFail();

        $pkgDesInfo = PackDestinationInfo::where('package_uuid', $uuid)->first();
        $pkgQuatDetail = PackQuatDetail::where('package_uuid', $uuid)->first();
        $pkgAccomoDetail = PackAccomoDetail::where('package_uuid', $uuid)->first();
        $pkgPrice = PackPrice::where('package_uuid', $uuid)->first();
        $pkgItenaries = PackItenaries::where('package_uuid', $uuid)->get();
        $pkgInclusions =  PackInclusion::where('package_uuid', $uuid)->first();

        $title = "Itinerary Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 7;

        return view('backend.packages.show', [
            'package' => $pkg,
            'packDestinationInfo' => $pkgDesInfo ?? 'No Data Found',
            'packQuatDetail' => $pkgQuatDetail ?? 'No Data Found',
            'packAccomoDetail' => $pkgAccomoDetail ?? 'No Data Found',
            'packPrice' => $pkgPrice ?? 'No Data Found',
            'packItenaries' => $pkgItenaries ?? [],
            'packInclusion' => $pkgInclusions ?? 'No Data Found',
            'title' => $title,
            'completedStep' => $completedStep,
            'uuid' => $uuid
        ])->with('success', 'Package completed successfully.');
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

    public function destroy($uuid)
    {
        $package = Package::where('uuid', $uuid);
        $package->delete(); // this is soft delete

        return redirect()->route('backend.packages.index')->with('success', 'Packages moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = Package::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.packages.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $package = Package::onlyTrashed()->where('uuid', $uuid);
        $package->restore();

        return redirect()->route('backend.packages.trash')->with('success', 'package restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $package = Package::onlyTrashed()->where('uuid', $uuid);
        $package->forceDelete();

        return redirect()->route('backend.packages.trash')->with('success', 'package permanently deleted.');
    }

    public function fnPackageDetails($uuid)
    {
        $pkg = Package::where('uuid', $uuid)->firstOrFail();

        $pkgDesInfo = PackDestinationInfo::where('package_uuid', $uuid)->first();
        $pkgQuatDetail = PackQuatDetail::where('package_uuid', $uuid)->first();
        $pkgAccomoDetail = PackAccomoDetail::where('package_uuid', $uuid)->first();
        $pkgPrice = PackPrice::where('package_uuid', $uuid)->first();
        $pkgItenaries = PackItenaries::where('package_uuid', $uuid)->get();
        $pkgInclusions =  PackInclusion::where('package_uuid', $uuid)->first();

        return view('frontend.package-details', [
            'package' => $pkg,
            'packDestinationInfo' => $pkgDesInfo ?? 'No Data Found',
            'packQuatDetail' => $pkgQuatDetail ?? 'No Data Found',
            'packAccomoDetail' => $pkgAccomoDetail ?? 'No Data Found',
            'packPrice' => $pkgPrice ?? 'No Data Found',
            'packItenaries' => $pkgItenaries ?? [],
            'packInclusion' => $pkgInclusions ?? 'No Data Found',
            'uuid' => $uuid
        ]);
    }

    public function packPdf($uuid)
    {
        $pkg = Package::where('uuid', $uuid)->firstOrFail();
        $pkgDesInfo = PackDestinationInfo::where('package_uuid', $uuid)->first();
        $pkgQuatDetail = PackQuatDetail::where('package_uuid', $uuid)->first();
        $pkgAccomoDetail = PackAccomoDetail::where('package_uuid', $uuid)->first();
        $pkgPrice = PackPrice::where('package_uuid', $uuid)->first();
        $pkgItenaries = PackItenaries::where('package_uuid', $uuid)->get();
        $pkgInclusions = PackInclusion::where('package_uuid', $uuid)->first();

        $title = "Itinerary Details";
        $package = $this->getPackageInfo($uuid);
        $completedStep = $package->progress_step ?? 7;

        $fontDirs = (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];
        $fontDirs[] = realpath(public_path('fonts/Poppins'));

        $fontData = (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'];
        $fontData['poppins'] = [
            'R' => 'Poppins-Regular.ttf',
            'B' => 'Poppins-Bold.ttf',
        ];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => __DIR__ . '/tmp',
            'margin_top' => 50,
            'margin_bottom' => 20,
            'margin_left' => 15,
            'margin_right' => 15,

            'fontDir' => $fontDirs,
            'fontdata' => $fontData,

            'default_font' => 'poppins'
        ]);


        // --- Set Header and Footer dynamically ---
        $headerHtml = '<table style="width: 100%; text-align: left !important;">
                    <tr>
                        <td style="width: 30%;">
                            <img src="' . asset("ui/backend/assets/build/images/logo.png") . '" style="width: 120px;"/>
                        </td>
                        <td style="width: 20%; border-bottom: 1px solid; border-collapse: collapse;"></td>
                        <td style="width: 50%; border-bottom: 1px solid; border-collapse: collapse;">
                            <table style="width: 100%; border: 0px solid; border-collapse: collapse; text-align: left !important;">
                                <tr>
                                    <td style="width: 10%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="12" width="12" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#49ba7e" d="M224.2 89C216.3 70.1 195.7 60.1 176.1 65.4L170.6 66.9C106 84.5 50.8 147.1 66.9 223.3C104 398.3 241.7 536 416.7 573.1C493 589.3 555.5 534 573.1 469.4L574.6 463.9C580 444.2 569.9 423.6 551.1 415.8L453.8 375.3C437.3 368.4 418.2 373.2 406.8 387.1L368.2 434.3C297.9 399.4 241.3 341 208.8 269.3L253 233.3C266.9 222 271.6 202.9 264.8 186.3L224.2 89z"/></svg>
                                    </td>
                                    <td style="width: 90%;">
                                        +8801611482773
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="12" width="12" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#49ba7e" d="M112 128C85.5 128 64 149.5 64 176C64 191.1 71.1 205.3 83.2 214.4L291.2 370.4C308.3 383.2 331.7 383.2 348.8 370.4L556.8 214.4C568.9 205.3 576 191.1 576 176C576 149.5 554.5 128 528 128L112 128zM64 260L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 260L377.6 408.8C343.5 434.4 296.5 434.4 262.4 408.8L64 260z"/></svg>
                                    </td>
                                    <td style="width: 90%;">
                                        travhubxyz@gmail.com
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="12" width="12" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#49ba7e" d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"/></svg>
                                    </td>
                                    <td style="width: 90%;">
                                        H# 01, R# 06, Sec# 03, Uttara, Dhaka-1230
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
        $footerHtml = '<div style="text-align: center; font-size: 10px;">Page {PAGENO} of {nbpg}</div>';

        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);

        // Render Blade view as HTML string
        $html = view('backend.packages.pdf', [
            'package' => $pkg,
            'packDestinationInfo' => $pkgDesInfo ?? 'No Data Found',
            'packQuatDetail' => $pkgQuatDetail ?? 'No Data Found',
            'packAccomoDetail' => $pkgAccomoDetail ?? 'No Data Found',
            'packPrice' => $pkgPrice ?? 'No Data Found',
            'packItenaries' => $pkgItenaries ?? [],
            'packInclusion' => $pkgInclusions ?? 'No Data Found',
            'title' => $title,
            'completedStep' => $completedStep,
            'uuid' => $uuid
        ])->render();

        $mpdf->WriteHTML($html);

        // Output PDF inline
        return $mpdf->Output('Package_' . $uuid . '.pdf', 'I');
    }
}
