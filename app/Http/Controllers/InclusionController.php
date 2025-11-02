<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Inclusion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InclusionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inclusions = Inclusion::orderBy('id', 'asc')->paginate(10);
        return view('backend.inclusions.index', compact('inclusions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activities = Activity::get();
        return view('backend.inclusions.create', compact('activities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'activity_uuid' => 'required|string'
        ]);

        $activity = Activity::where('uuid', $request->activity_uuid)->first();

        try {
            $inclusion = Inclusion::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'activity_uuid' => $request->activity_uuid,
                'activity_id' => $activity->id,
                'activity_title' => $activity->title,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('inclusions.index')->with('success', 'Inclusion created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($inclusion)
    {
        $inclusion = Inclusion::where('uuid', $inclusion)->first();
        return view('backend.inclusions.show', compact('inclusion'));
    }

    public function edit($inclusion)
    {
        $inclusion = Inclusion::where('uuid', $inclusion)->first();
        return view('backend.inclusions.edit', compact('inclusion'));
    }

    public function update(Request $request, Inclusion $inclusion)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        try {
            $inclusion->update([
                'title' => $request->title,
                'country_code' => $request->country_code,
                'updated_by' => Auth::user()->id,
                'status' =>  $request->input('status') == '1' ? 'active' : 'inactive'
            ]);

            return redirect()->route('inclusions.index')->with('success', 'Inclusion updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy($uuid)
    {
        $inclusion = Inclusion::where('uuid', $uuid);
        $inclusion->delete(); // this is soft delete

        return redirect()->route('inclusions.index')->with('success', 'Inclusion moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = Inclusion::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.inclusions.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $inclusion = Inclusion::onlyTrashed()->where('uuid', $uuid);
        $inclusion->restore();

        return redirect()->route('inclusions.trash')->with('success', 'Inclusion restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $inclusion = Inclusion::onlyTrashed()->where('uuid', $uuid);
        $inclusion->forceDelete();

        return redirect()->route('inclusions.trash')->with('success', 'Inclusion permanently deleted.');
    }

    public function getData(Request $request)
    {
        try {
            $query = Inclusion::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $inclusions = $query->orderBy('created_at', 'asc')->paginate(10);
            return response()->json($inclusions);
        } catch (\Throwable $e) {
            \Log::error('Countrise getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = Inclusion::with('createdBy');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $inclusions = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>".companyName()."</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.inclusions.pdf', compact('inclusions'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
}
