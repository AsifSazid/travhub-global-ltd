<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::orderBy('id', 'asc')->paginate(10);
        return view('backend.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::get();
        return view('backend.currencies.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'currency_code' => 'required|string',
            'icon' => 'nullable|string',
            'country_uuid' => 'required'
        ]);

        $country_data = Country::where('uuid', $request->country_uuid)->first();

        try {
            $currency = Currency::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'currency_code' => $request->currency_code,
                'icon' => $request->icon,
                'country_uuid' => $request->country_uuid,
                'country_id' => $country_data->id,
                'country_title' => $country_data->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('currencies.index')->with('success', 'Currency created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($currency)
    {
        $currency = Currency::where('uuid', $currency)->firstOrFail();
        return view('backend.currencies.show', compact('currency'));
    }

    public function edit($currency)
    {
        $countries = Country::get();
        $currency = Currency::where('uuid', $currency)->first();
        return view('backend.currencies.edit', compact('currency', 'countries'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'title' => 'required|string',
            'currency_code' => 'required|string',
            'icon' => 'nullable|string',
            'country_uuid' => 'required',
            'status' => 'nullable|boolean',
        ]);

        $country_data = Country::where('uuid', $request->country_uuid)->first();

        try {
            $currency->update([
                'title' => $request->title,
                'currency_code' => $request->currency_code,
                'icon' => $request->icon,
                'country_uuid' => $request->country_uuid,
                'country_id' => $country_data->id,
                'country_title' => $country_data->title,
                'updated_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('currencies.index')->with('success', 'Currency updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $currency = Currency::where('uuid', $uuid);
        $currency->delete(); // this is soft delete

        return redirect()->route('currencies.index')->with('success', 'Currency moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = Currency::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.currencies.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $currency = Currency::onlyTrashed()->where('uuid', $uuid);
        $currency->restore();

        return redirect()->route('currencies.trash')->with('success', 'Currency restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $currency = Currency::onlyTrashed()->where('uuid', $uuid);
        $currency->forceDelete();

        return redirect()->route('currencies.trash')->with('success', 'Currency permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = Currency::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $currencies = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($currencies);
        } catch (\Throwable $e) {
            \Log::error('Countrise getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = Currency::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $currencies = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>" . companyName() . "</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.currencies.pdf', compact('currencies'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
}
