<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::orderBy('id', 'asc')->paginate(10);
        return view('backend.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'country_code' => 'required|string'
        ]);

        try {
            $country = Country::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'country_code' => $request->country_code,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('countries.index')->with('success', 'Country created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($country)
    {
        $country = Country::where('uuid', $country)->withCount('cities')->firstOrFail();
        return view('backend.countries.show', compact('country'));
    }

    public function edit($country)
    {
        $country = Country::where('uuid', $country)->first();
        return view('backend.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        try {
            $country->update([
                'title' => $request->title,
                'country_code' => $request->country_code,
                'updated_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('countries.index')->with('success', 'Country updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $country = Country::where('uuid', $uuid);
        $country->delete(); // this is soft delete

        return redirect()->route('countries.index')->with('success', 'Country moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = Country::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.countries.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $country = Country::onlyTrashed()->where('uuid', $uuid);
        $country->restore();

        return redirect()->route('countries.trash')->with('success', 'Country restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $country = Country::onlyTrashed()->where('uuid', $uuid);
        $country->forceDelete();

        return redirect()->route('countries.trash')->with('success', 'Country permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = Country::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $countries = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($countries);
        } catch (\Throwable $e) {
            \Log::error('Countrise getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = Country::with('createdBy');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $countries = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>" . companyName() . "</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.countries.pdf', compact('countries'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }

    public function getCities($id)
    {
        $cities = \App\Models\City::where('country_id', $id)
            ->where('status', 'active')
            ->get(['id', 'uuid', 'title']);

        return response()->json($cities);
    }
}
