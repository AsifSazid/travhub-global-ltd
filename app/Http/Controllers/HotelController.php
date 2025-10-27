<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::orderBy('id', 'asc')->paginate(10);
        return view('backend.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::get();
        return view('backend.hotels.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'city_uuid' => 'required'
        ]);

        $city_date = City::where('uuid', $request->city_uuid)->first();

        try {
            $hotel = Hotel::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'city_uuid' => $request->city_uuid,
                'city_id' => $city_date->id,
                'city_title' => $city_date->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('hotels.index')->with('success', 'Hotel created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($hotel)
    {
        $hotel = Hotel::where('uuid', $hotel)->first();
        return view('backend.hotels.show', compact('hotel'));
    }

    public function edit($hotel)
    {
        $hotel = Hotel::where('uuid', $hotel)->first();
        $cities = City::get();

        return view('backend.hotels.edit', compact('hotel','cities'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'title' => 'required|string',
            'city_uuid' => 'required',
        ]);

        $city_data = City::where('uuid', $request->city_uuid)->first();

        try {
            $hotel->update([
                'title' => $request->title,
                'city_uuid' => $request->city_uuid,
                'city_id' => $city_data->id,
                'city_title' => $city_data->title,
                'created_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('hotels.index')->with('success', 'Hotel updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $hotel = Hotel::where('uuid', $uuid);
        $hotel->delete(); // this is soft delete

        return redirect()->route('hotels.index')->with('success', 'Hotel moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = Hotel::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.hotels.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $hotel = Hotel::onlyTrashed()->where('uuid', $uuid);
        $hotel->restore();

        return redirect()->route('hotels.trash')->with('success', 'Hotel restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $hotel = Hotel::onlyTrashed()->where('uuid', $uuid);
        $hotel->forceDelete();

        return redirect()->route('hotels.trash')->with('success', 'Hotel permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = Hotel::with('city', 'user');

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $hotels = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($hotels);
        } catch (\Throwable $e) {
            \Log::error('Hotes getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = Hotel::with('user');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $hotels = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>" . companyName() . "</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.hotels.pdf', compact('hotels'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
}
