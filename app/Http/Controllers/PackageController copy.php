<?php

namespace App\Http\Controllers;

use App\Models\PackAccomoDetail;
use App\Models\Package;
use App\Models\PackDestinationInfo;
use App\Models\PackInclusion;
use App\Models\PackItenaries;
use App\Models\PackPrice;
use App\Models\PackQuatDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::orderBy('id', 'asc')->paginate(10);
        return view('backend.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $uuid = $request->query('uuid');
        $step = $request->query('step', 1);

        $package = null;
        if ($uuid) {
            $package = \App\Models\Package::where('uuid', $uuid)->first();
        }
        return view('backend.packages.create-multistep', compact('package', 'uuid', 'step'));
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
            $package = Package::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('packages.index')->with('success', 'Package created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($package)
    {
        $package = Package::where('uuid', $package)->first();
        return view('backend.packages.show', compact('package'));
    }

    public function edit($package)
    {
        $package = Package::where('uuid', $package)->first();
        return view('backend.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        try {
            $package->update([
                'title' => $request->title,
                'updated_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('packages.index')->with('success', 'Package updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $package = Package::where('uuid', $uuid);
        $package->delete(); // this is soft delete

        return redirect()->route('packages.index')->with('success', 'Package moved to trash.');
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

        return redirect()->route('packages.trash')->with('success', 'Package restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $package = Package::onlyTrashed()->where('uuid', $uuid);
        $package->forceDelete();

        return redirect()->route('packages.trash')->with('success', 'Package permanently deleted.');
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

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = Package::with('user');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $packages = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>" . companyName() . "</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.packages.pdf', compact('packages'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }

    // Step 1: create base package row
    public function stepOne(Request $request)
    {
        $v = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $data = $v->validated();

        $pkg = Package::create([
            'uuid' => (string) Str::uuid(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
            'created_by' => Auth::user()->id ?? null,
        ]);

        return response()->json(['message' => 'Step 1 saved', 'data' => ['package_id' => $pkg->id, 'package_uuid' => $pkg->uuid, 'package_title' => $pkg->title]], 200);
    }

    // Step 2: destination info -> pack_destination_infos
    public function stepTwo(Request $request)
    {
        dd($request->all());
        $v = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'country_id' => 'required|exists:countries,id',
            'activity_category_id' => 'required|exists:activity_categories,id',
            'cities' => 'nullable|string'
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $d = $v->validated();

        // fetch package references
        $pkg = Package::findOrFail($d['package_id']);

        $row = PackDestinationInfo::create([
            'uuid' => (string) Str::uuid(),
            'title' => $pkg->title . ' - dest',
            'description' => null,
            'status' => 'active',
            'icon' => null,
            'package_id' => $pkg->id,
            'package_uuid' => $pkg->uuid,
            'package_title' => $pkg->title,
            'country_id' => $d['country_id'],
            // set country references if you want:
            'country_uuid' => optional(\App\Models\Country::find($d['country_id']))->uuid,
            'country_title' => optional(\App\Models\Country::find($d['country_id']))->title,
            'cities' => $d['cities'] ? json_encode(array_map('trim', explode(',', $d['cities']))) : null,
            'activity_category_id' => $d['activity_category_id'],
            'activity_uuid' => optional(\App\Models\ActivityCategory::find($d['activity_category_id']))->uuid,
            'activity_title' => optional(\App\Models\ActivityCategory::find($d['activity_category_id']))->title,
            'created_by' => Auth::user()->id ?? null,
        ]);

        return response()->json(['message' => 'Step 2 saved', 'data' => ['destination_id' => $row->id]], 200);
    }

    // Step 3: quantity/quotation details -> pack_quat_details
    public function stepThree(Request $request)
    {
        $v = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'duration' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'no_of_pax' => 'nullable|string' // allow JSON string
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $d = $v->validated();
        $pkg = Package::findOrFail($d['package_id']);

        $row = PackQuatDetail::create([
            'uuid' => (string) Str::uuid(),
            'title' => $pkg->title . ' - quat',
            'description' => null,
            'status' => 'active',
            'icon' => null,
            'package_id' => $pkg->id,
            'package_uuid' => $pkg->uuid,
            'package_title' => $pkg->title,
            'duration' => $d['duration'] ?? null,
            'start_date' => $d['start_date'] ?? null,
            'end_date' => $d['end_date'] ?? null,
            'destinations' => null,
            'quat_creating_for' => null,
            'no_of_pax' => $d['no_of_pax'] ? $d['no_of_pax'] : null,
            'created_by' => Auth::user()->id ?? null,
        ]);

        return response()->json(['message' => 'Step 3 saved', 'data' => ['quat_id' => $row->id]], 200);
    }

    // Step 4: accommodation details -> pack_accomo_details
    public function stepFour(Request $request)
    {
        $v = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'cities' => 'nullable|string',
            'hotels' => 'nullable|string' // expect JSON string
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $d = $v->validated();
        $pkg = Package::findOrFail($d['package_id']);

        $row = PackAccomoDetail::create([
            'uuid' => (string) Str::uuid(),
            'title' => $pkg->title . ' - accom',
            'description' => null,
            'status' => 'active',
            'icon' => null,
            'package_id' => $pkg->id,
            'package_uuid' => $pkg->uuid,
            'package_title' => $pkg->title,
            'cities' => $d['cities'] ? json_encode(array_map('trim', explode(',', $d['cities']))) : null,
            'hotels' => $d['hotels'] ? $d['hotels'] : null,
            'created_by' => Auth::user()->id ?? null,
        ]);

        return response()->json(['message' => 'Step 4 saved', 'data' => ['accomo_id' => $row->id]], 200);
    }

    public function stepFive(Request $request)
    {
        $v = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'air_ticket_details' => 'nullable|string',
            'price_options' => 'nullable|string'
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $d = $v->validated();
        $pkg = Package::findOrFail($d['package_id']);

        // convert simple CSV lines to json price options
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

        $row = PackPrice::create([
            'uuid' => (string) Str::uuid(),
            'title' => $pkg->title . ' - price',
            'description' => null,
            'status' => 'active',
            'icon' => null,
            'package_id' => $pkg->id,
            'package_uuid' => $pkg->uuid,
            'package_title' => $pkg->title,
            'currency_id' => $d['currency_id'] ?? null,
            'currency_uuid' => $d['currency_id'] ? optional(\App\Models\Currency::find($d['currency_id']))->uuid : null,
            'currency_title' => $d['currency_id'] ? optional(\App\Models\Currency::find($d['currency_id']))->title : null,
            'air_ticket_details' => $d['air_ticket_details'] ?? null,
            'air_ticket_details_json' => $price_json, // optional extra column if you add it
            'created_by' => Auth::user()->id ?? null,
        ]);

        return response()->json(['message' => 'Step 5 saved', 'data' => ['price_id' => $row->id]], 200);
    }

    // Step 6: itineraries + inclusions -> pack_itenaries + pack_inclusions
    public function stepSix(Request $request)
    {
        $v = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'itenaries' => 'nullable|string',
            'inclusions' => 'nullable|string'
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $d = $v->validated();
        $pkg = Package::findOrFail($d['package_id']);

        DB::beginTransaction();
        try {
            // Store itineraries if provided (expects JSON array)
            if (!empty($d['itenaries'])) {
                $items = json_decode($d['itenaries'], true);
                if (is_array($items)) {
                    foreach ($items as $it) {
                        PackItenaries::create([
                            'uuid' => (string) Str::uuid(),
                            'title' => $it['title'] ?? ($pkg->title . ' itenary day'),
                            'description' => $it['description'] ?? null,
                            'status' => 'active',
                            'icon' => null,
                            'package_id' => $pkg->id,
                            'package_uuid' => $pkg->uuid,
                            'package_title' => $pkg->title,
                            // other reference fields can be mapped as needed
                            'created_by' => Auth::user()->id ?? null,
                        ]);
                    }
                }
            }

            // Inclusions (comma separated) -> pack_inclusions (we'll map to inclusion_id if exist)
            if (!empty($d['inclusions'])) {
                $incs = array_map('trim', explode(',', $d['inclusions']));
                foreach ($incs as $incTitle) {
                    $pi = PackInclusion::create([
                        'uuid' => (string) Str::uuid(),
                        'title' => $incTitle,
                        'description' => null,
                        'status' => 'active',
                        'icon' => null,
                        'package_id' => $pkg->id,
                        'package_uuid' => $pkg->uuid,
                        'package_title' => $pkg->title,
                        'inclusion_id' => null, // map to existing inclusion if you want
                        'inclusion_uuid' => null,
                        'inclusion_title' => $incTitle,
                        'created_by' => Auth::user()->id ?? null,
                    ]);
                }
            }

            // Optionally mark package as completed/finalized
            $pkg->update(['updated_by' => Auth::user()->id ?? null]);

            DB::commit();
            return response()->json(['message' => 'Package created successfully', 'data' => ['package_id' => $pkg->id]], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to save final step: ' . $e->getMessage()], 500);
        }
    }
}
