<?php

namespace App\Http\Controllers;

use App\Models\CurrentRate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrentRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $current_rates = CurrentRate::orderBy('id', 'asc')->paginate(10);
        return view('backend.current_rates.index', compact('current_rates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.current_rates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'rate_value' => 'required|string'
        ]);

        try {
            CurrentRate::where('status', 'active')->where('title', $request->title)->update(['status' => 'inactive']);

            $current_rate = CurrentRate::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'rate_value' => $request->rate_value,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('current_rates.index')->with('success', 'CurrencyRate created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($current_rate)
    {
        $current_rate = CurrentRate::where('uuid', $current_rate)->first();
        $user = User::where('id', $current_rate->created_by)->first();
        return view('backend.current_rates.show', compact('current_rate', 'user'));
    }

    public function edit($current_rate)
    {
        $current_rate = CurrentRate::where('uuid', $current_rate)->first();
        return view('backend.current_rates.edit', compact('current_rate'));
    }

    public function update(Request $request, CurrentRate $current_rate)
    {
        $request->validate([
            'title' => 'required|string',
            'rate_value' => 'required',
        ]);

        try {
            $current_rate->update([
                'title' => $request->title,
                'rate_value' => $request->rate_value,
                'updated_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('current_rates.index')->with('success', 'CurrencyRate updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $current_rate = CurrentRate::where('uuid', $uuid);
        $current_rate->delete(); // this is soft delete

        return redirect()->route('current_rates.index')->with('success', 'CurrencyRate moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = CurrentRate::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.current_rates.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $current_rate = CurrentRate::onlyTrashed()->where('uuid', $uuid);
        $current_rate->restore();

        return redirect()->route('current_rates.trash')->with('success', 'CurrencyRate restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $current_rate = CurrentRate::onlyTrashed()->where('uuid', $uuid);
        $current_rate->forceDelete();

        return redirect()->route('current_rates.trash')->with('success', 'CurrencyRate permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = CurrentRate::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $current_rates = $query->orderBy('created_at', 'desc')->paginate(10);
            return response()->json($current_rates);
        } catch (\Throwable $e) {
            \Log::error('Countrise getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = CurrentRate::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $current_rates = $query->orderBy('created_at', 'desc')->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>".companyName()."</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.current_rates.pdf', compact('current_rates'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
}
