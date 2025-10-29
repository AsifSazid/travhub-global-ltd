<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
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
    public function create()
    {
        return view('backend.packages.create');
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
        $mpdf->SetHeader("<div style='text-align:center'>".companyName()."</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.packages.pdf', compact('packages'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
}
