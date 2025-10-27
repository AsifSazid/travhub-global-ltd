<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::orderBy('id', 'asc')->paginate(10);
        return view('backend.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::get();
        return view('backend.cities.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'country_uuid' => 'required'
        ]);

        $country_data = Country::where('uuid', $request->country_uuid)->first();

        try {
            $city = City::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'country_uuid' => $request->country_uuid,
                'country_id' => $country_data->id,
                'country_title' => $country_data->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('cities.index')->with('success', 'City created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($city)
    {
        $city = City::where('uuid', $city)->withCount('hotels')->first();
        return view('backend.cities.show', compact('city'));
    }

    public function edit($city)
    {
        $city = City::where('uuid', $city)->first();
        $countries = Country::get();

        return view('backend.cities.edit', compact('city','countries'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'title' => 'required|string',
            'country_uuid' => 'required',
        ]);

        $country_data = Country::where('uuid', $request->country_uuid)->first();

        try {
            $city->update([
                'title' => $request->title,
                'country_uuid' => $request->country_uuid,
                'country_id' => $country_data->id,
                'country_title' => $country_data->title,
                'created_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('cities.index')->with('success', 'City updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $city = City::where('uuid', $uuid);
        $city->delete(); // this is soft delete

        return redirect()->route('cities.index')->with('success', 'City moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = City::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.cities.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $city = City::onlyTrashed()->where('uuid', $uuid);
        $city->restore();

        return redirect()->route('cities.trash')->with('success', 'City restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $city = City::onlyTrashed()->where('uuid', $uuid);
        $city->forceDelete();

        return redirect()->route('cities.trash')->with('success', 'City permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = City::with('country', 'user');

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $cities = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($cities);
        } catch (\Throwable $e) {
            \Log::error('Cities getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = City::with('user');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $cities = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>" . companyName() . "</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.cities.pdf', compact('cities'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
}
