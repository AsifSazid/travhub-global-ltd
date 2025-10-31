<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    Currency
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

    public function create(Request $request)
    {
        $uuid = $request->query('uuid');
        $step = $request->query('step', 1);

        $package = null;
        if ($uuid) {
            $package = Package::where('uuid', $uuid)->first();
            if ($package && !$package->is_complete && $package->progress_step) {
                $step = $package->progress_step;
            }
        }

        $countries = Country::where('status', 'active')->get();
        $activities = Activity::where('status', 'active')->where('activity_category_id', 1)->get();

        return view('backend.packages.create-multistep', compact('package', 'uuid', 'step', 'countries', 'activities'));
    }

    /**
     * Handle step submission dynamically
     */
    public function step(Request $request, $step)
    {
        switch ((int)$step) {
            case 1:
                $response = $this->stepOne($request);
                break;
            case 2:
                $response = $this->stepTwo($request);
                break;
            case 3:
                $response = $this->stepThree($request);
                break;
            case 4:
                $response = $this->stepFour($request);
                break;
            case 5:
                $response = $this->stepFive($request);
                break;
            case 6:
                $response = $this->stepSix($request);
                break;
            case 7:
                $response = $this->stepSeven($request);
                break;
            default:
                return redirect()->back()->withErrors(['Invalid step']);
        }

        // If everything ok, redirect to next step (POST/Redirect/GET pattern)
        if ($response->status() === 200) {
            $nextStep = $step < 7 ? $step + 1 : 7;
            return redirect()->route('packages.create', ['uuid' => $request->uuid, 'step' => $nextStep])
                ->with('success', $response->getData()->message ?? 'Saved successfully');
        }

        return $response;
    }

    /**
     * Step 1: Basic package info
     */
    protected function stepOne(Request $request)
    {
        $v = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);

        if ($v->fails()) return redirect()->back()->withErrors($v)->withInput();

        $data = $v->validated();

        $pkg = Package::updateOrCreate(
            ['uuid' => $request->uuid],
            [
                'uuid' => $request->uuid ?? Str::uuid(),
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? 'active',
                'created_by' => Auth::id()
            ]
        );

        $pkg->update(['progress_step' => 1]);

        return redirect()->route('packages.create', ['uuid' => $pkg->uuid, 'step' => 2])
            ->with('success', 'Step 1 saved.');
    }

    /**
     * Step 2: Destination info
     */
    protected function stepTwo(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'uuid' => 'required|exists:packages,uuid',
                'country_id' => 'required|exists:countries,id',
                'activity_id' => 'required|exists:activities,id',
                'cities' => 'nullable|string'
            ]);

            dd($v);
            if ($v->fails()) return redirect()->back()->withErrors($v)->withInput();

            $d = $v->validated();
            $pkg = Package::where('uuid', $d['uuid'])->firstOrFail();

            PackDestinationInfo::updateOrCreate(
                ['package_uuid' => $pkg->uuid],
                [
                    'uuid' => Str::uuid(),
                    'package_id' => $pkg->id,
                    'package_uuid' => $pkg->uuid,
                    'package_title' => $pkg->title,
                    'country_id' => $d['country_id'],
                    'country_uuid' => optional(Country::find($d['country_id']))->uuid,
                    'country_title' => optional(Country::find($d['country_id']))->title,
                    'activity_id' => $d['activity_id'],
                    'activity_uuid' => optional(Activity::find($d['activity_id']))->uuid,
                    'activity_title' => optional(Activity::find($d['activity_id']))->title,
                    'cities' => $d['cities'] ?? null,
                    'status' => 'active',
                    'created_by' => Auth::id()
                ]
            );

            $pkg->update(['progress_step' => 2]);

            return redirect()->route('packages.create', ['uuid' => $pkg->uuid, 'step' => 3])
                ->with('success', 'Step 2 saved.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Step 3: Quotation details
     */
    protected function stepThree(Request $request)
    {
        $v = Validator::make($request->all(), [
            'uuid' => 'required|exists:packages,uuid',
            'duration' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'no_of_pax' => 'nullable|string'
        ]);

        if ($v->fails()) return redirect()->back()->withErrors($v)->withInput();

        $d = $v->validated();
        $pkg = Package::where('uuid', $d['uuid'])->firstOrFail();

        PackQuatDetail::updateOrCreate(
            ['package_uuid' => $pkg->uuid],
            [
                'uuid' => Str::uuid(),
                'package_id' => $pkg->id,
                'package_uuid' => $pkg->uuid,
                'package_title' => $pkg->title,
                'duration' => $d['duration'] ?? null,
                'start_date' => $d['start_date'] ?? null,
                'end_date' => $d['end_date'] ?? null,
                'no_of_pax' => $d['no_of_pax'] ?? null,
                'status' => 'active',
                'created_by' => Auth::id()
            ]
        );

        $pkg->update(['progress_step' => 3]);

        return redirect()->route('packages.create', ['uuid' => $pkg->uuid, 'step' => 4])
            ->with('success', 'Step 3 saved.');
    }

    /**
     * Step 4: Accommodation details
     */
    protected function stepFour(Request $request)
    {
        $v = Validator::make($request->all(), [
            'uuid' => 'required|exists:packages,uuid',
            'accomo_cities' => 'nullable|string',
            'hotels' => 'nullable|string'
        ]);

        if ($v->fails()) return redirect()->back()->withErrors($v)->withInput();

        $d = $v->validated();
        $pkg = Package::where('uuid', $d['uuid'])->firstOrFail();

        PackAccomoDetail::updateOrCreate(
            ['package_uuid' => $pkg->uuid],
            [
                'uuid' => Str::uuid(),
                'package_id' => $pkg->id,
                'package_uuid' => $pkg->uuid,
                'package_title' => $pkg->title,
                'cities' => $d['accomo_cities'] ?? null,
                'hotels' => $d['hotels'] ?? null,
                'status' => 'active',
                'created_by' => Auth::id()
            ]
        );

        $pkg->update(['progress_step' => 4]);

        return redirect()->route('packages.create', ['uuid' => $pkg->uuid, 'step' => 5])
            ->with('success', 'Step 4 saved.');
    }

    /**
     * Step 5: Pricing info
     */
    protected function stepFive(Request $request)
    {
        $v = Validator::make($request->all(), [
            'uuid' => 'required|exists:packages,uuid',
            'currency_id' => 'nullable|exists:currencies,id',
            'air_ticket_details' => 'nullable|string',
            'price_options' => 'nullable|string'
        ]);

        if ($v->fails()) return redirect()->back()->withErrors($v)->withInput();

        $d = $v->validated();
        $pkg = Package::where('uuid', $d['uuid'])->firstOrFail();

        // Convert CSV to JSON
        $price_json = null;
        if (!empty($d['price_options'])) {
            $lines = preg_split("/\r\n|\n|\r/", trim($d['price_options']));
            $out = [];
            foreach ($lines as $ln) {
                $parts = array_map('trim', explode('|', $ln));
                if (count($parts) >= 2) {
                    $out[] = ['option' => $parts[0], 'price_per_person' => $parts[1]];
                }
            }
            $price_json = json_encode($out);
        }

        PackPrice::updateOrCreate(
            ['package_uuid' => $pkg->uuid],
            [
                'uuid' => Str::uuid(),
                'package_id' => $pkg->id,
                'package_uuid' => $pkg->uuid,
                'package_title' => $pkg->title,
                'currency_id' => $d['currency_id'] ?? null,
                'currency_uuid' => optional(Currency::find($d['currency_id']))->uuid,
                'currency_title' => optional(Currency::find($d['currency_id']))->title,
                'air_ticket_details' => $d['air_ticket_details'] ?? null,
                'price_options' => $price_json,
                'status' => 'active',
                'created_by' => Auth::id()
            ]
        );

        $pkg->update(['progress_step' => 5]);

        return redirect()->route('packages.create', ['uuid' => $pkg->uuid, 'step' => 6])
            ->with('success', 'Step 5 saved.');
    }

    /**
     * Step 6: Itineraries & inclusions
     */
    protected function stepSix(Request $request)
    {
        $v = Validator::make($request->all(), [
            'uuid' => 'required|exists:packages,uuid',
            'itenaries' => 'nullable|string',
            'inclusions' => 'nullable|string'
        ]);

        if ($v->fails()) return redirect()->back()->withErrors($v)->withInput();

        $d = $v->validated();
        $pkg = Package::where('uuid', $d['uuid'])->firstOrFail();

        DB::beginTransaction();
        try {
            // Itineraries
            if (!empty($d['itenaries'])) {
                $items = json_decode($d['itenaries'], true);
                if (is_array($items)) {
                    foreach ($items as $it) {
                        PackItenaries::updateOrCreate(
                            ['package_uuid' => $pkg->uuid, 'title' => $it['title']],
                            [
                                'uuid' => Str::uuid(),
                                'package_id' => $pkg->id,
                                'package_uuid' => $pkg->uuid,
                                'package_title' => $pkg->title,
                                'description' => $it['description'] ?? null,
                                'status' => 'active',
                                'created_by' => Auth::id()
                            ]
                        );
                    }
                }
            }

            // Inclusions
            if (!empty($d['inclusions'])) {
                $incs = array_map('trim', explode(',', $d['inclusions']));
                foreach ($incs as $title) {
                    PackInclusion::updateOrCreate(
                        ['package_uuid' => $pkg->uuid, 'title' => $title],
                        [
                            'uuid' => Str::uuid(),
                            'package_id' => $pkg->id,
                            'package_uuid' => $pkg->uuid,
                            'package_title' => $pkg->title,
                            'status' => 'active',
                            'created_by' => Auth::id()
                        ]
                    );
                }
            }

            DB::commit();
            $pkg->update(['progress_step' => 6]);

            return redirect()->route('packages.create', ['uuid' => $pkg->uuid, 'step' => 7])
                ->with('success', 'Step 6 saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Step 7: Finalize package
     */
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
}
