<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use App\Models\Wing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NavigationController extends Controller
{
    public function index()
    {
        $navigationCollection = Navigation::latest();
        $navigations = $navigationCollection->paginate(10);
        return view('backend.navigations.index', compact('navigations'));
    }

    public function create()
    {
        $wings = Wing::get();
        $navigations = Navigation::get();
        return view('backend.navigations.create', compact('wings', 'navigations'));
    }

    public function store(Request $request)
    {
        $request['is_active'] = $request->has('is_active') ? 1 : 0;

        $request->validate([
            'title' => 'required|string',
            'nav_icon' => 'string',
            'url' => 'string',
            'route' => 'string',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $wing = $request->navigation_for
                ? Wing::find($request->navigation_for)
                : null;

            $navigation = Navigation::create([
                'uuid' => (string) \Str::uuid(),
                'title' => $request->title,
                'nav_icon' => $request->nav_icon,
                'url' => $request->url,
                'route' => $request->route,
                'parent_id' => $request->parent_id ?? null,
                'navigation_for' => $wing->id ?? null,
                'navigation_for_title' => $wing->title ?? null,
                'navigation_for_uuid' => $wing->uuid ?? null,
                'subdomain' => $wing->subdomain ?? null,
                'created_by' => Auth::user()->id,
                'created_by_uuid' => Auth::user()->uuid,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.navigations.index')->with('success', 'Navigation created successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function show($navigation)
    {
        $navigation = Navigation::where('uuid', $navigation)->first();
        return view('backend.navigations.show', compact('navigation'));
    }

    public function edit($navigation)
    {
        $navigation = Navigation::where('uuid', $navigation)->first();
        $navigations = Navigation::where('uuid', '!=', $navigation)->get();
        $wings = Wing::get();
        return view('backend.navigations.edit', compact('navigation', 'navigations', 'wings'));
    }

    public function update(Request $request, Navigation $navigation)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'navigation_for' => 'nullable|exists:wings,id',
                'nav_icon' => 'max:255',
                'url' => 'max:255',
                'route' => 'max:255',
                'parent_id' => 'nullable|exists:navigations,id|not_in:' . $navigation, // নিজেকে parent বানানো যাবে না
                'is_active' => 'nullable|boolean',
            ]);

            $navigation->update([
                'title' => $validated['title'],
                'navigation_for' => $validated['navigation_for'] ?? null,
                'nav_icon' => $validated['nav_icon'],
                'url' => $validated['url'],
                'route' => $validated['route'],
                'parent_id' => $validated['parent_id'] ?? null,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.navigations.index')->with('success', 'Navigation updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }


    public function destroy($uuid)
    {
        $navigation = Navigation::where('uuid', $uuid);
        $navigation->delete(); // this is soft delete

        return redirect()->route('admin.navigations.index')->with('success', 'Navigation moved to trash.');
    }

    public function trash()
    {
        $trashedCollection = Navigation::onlyTrashed()->latest();
        $trashed = $trashedCollection->paginate(10);
        return view('backend.navigations.trash', compact('trashed'));
    }

    public function restore($uuid)
    {
        $navigation = Navigation::onlyTrashed()->where('uuid', $uuid);
        $navigation->restore();

        return redirect()->route('admin.navigations.trash')->with('success', 'Navigation restored successfully.');
    }

    public function forceDelete($uuid)
    {
        $navigation = Navigation::onlyTrashed()->where('uuid', $uuid);
        $navigation->forceDelete();

        return redirect()->route('admin.navigations.trash')->with('success', 'Navigation permanently deleted.');
    }

    public function getData(Request $request)
    {
        $query = Navigation::with('user');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $navigations = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($navigations);
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->get('search');

        $query = Navigation::with('user');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $navigations = $query->get();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHeader("<div style='text-align:center'>Navigation List!</div>");
        $mpdf->SetFooter("This is a system generated document(s). So no need to show external signature or seal!");
        $view = view('backend.navigations.pdf', compact('navigations'));
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }

    public function syncRoutes()
    {
        $routes = Route::getRoutes();
        $inserted = [];

        $parents = [];

        foreach ($routes as $route) {
            $routeName = $route->getName();
            $uri = $route->uri();
            $action = $route->getActionName();

            if (!$routeName || $action === 'Closure') continue;

            $actionMethod = Str::after($action, '@');
            $ignored = [
                'store',
                'update',
                'destroy',
                'edit',
                'show',
                'trash',
                'restore',
                'forceDelete',
                'getData',
                'download',
                'downloadPdf'
            ];
            if (in_array($actionMethod, $ignored)) continue;

            // Route group base
            $base = Str::before($routeName, '.');

            // Subdomain detection (improved)
            $subdomain = '';
            if (isset($route->action['domain'])) {
                $fullDomain = $route->action['domain'];
                $subdomain = Str::before($fullDomain, '.');
            } else {
                $firstSegment = explode('/', $uri)[0] ?? '';
                $subdomain = in_array($firstSegment, ['publication', 'admin', 'seller']) ? $firstSegment : null;
            }

            // Parent nav (e.g., "Ebooks")
            if (!isset($parents[$base])) {
                $parent = Navigation::firstOrCreate(
                    ['title' => Str::headline(Str::plural($base)), 'parent_id' => null],
                    [
                        'uuid' => Str::uuid(),
                        'url' => '',
                        'route' => '',
                        'nav_icon' => '',
                        'subdomain' => $subdomain,
                        'created_by' => Auth::id() ?? 1,
                        'created_by_uuid' => optional(Auth::user())->uuid,
                        'is_active' => true,
                    ]
                );

                $parents[$base] = $parent->id;
            }

            if (Navigation::where('route', $routeName)->exists()) continue;

            // Final insert
            $inserted[] = Navigation::create([
                'uuid' => Str::uuid(),
                'title' => Str::headline(Str::after($routeName, $base . '.')), // "index" → "Index"
                'url' => '/' . ltrim($uri, '/'),
                'route' => $routeName,
                'nav_icon' => '',
                'subdomain' => $subdomain,
                'created_by' => Auth::id() ?? 1,
                'created_by_uuid' => optional(Auth::user())->uuid,
                'is_active' => true,
                'parent_id' => $parents[$base],
            ]);
        }

        return response()->json([
            'status' => 'done',
            'inserted_count' => count($inserted),
        ]);
    }


    public function getSidebarNavigation($subdomain = null)
    {
        // $subdomain = request()->getHost(); // or however you detect
        $query = Navigation::query()
            ->whereNull('parent_id')
            ->where('is_active', true);

        // if ($subdomain) {
        //     $query->where('subdomain', $subdomain);
        // }

        $navigations = $query->with(['children' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        // dd($navigations);

        return view('backend.navigations.sidebar', compact('navigations'));
        // return response()->json($formatted);
    }
}
