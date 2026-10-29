<?php

namespace App\Http\Controllers;

use App\Models\DisasterAlert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisasterAlertController extends Controller
{
    public function index(): View
    {
        $totalAlerts = DisasterAlert::count();
        $activeAlerts = DisasterAlert::where('status', 'active')->count();
        $resolvedAlerts = DisasterAlert::where('status', 'resolved')->count();
        $lastUpdated = DisasterAlert::latest('updated_at')->value('updated_at');

        $category = request('category');
        $severity = request('severity');
        $status = request('status');
        $search = trim((string) request('q', ''));

        $query = DisasterAlert::query()->latest('published_at');

        if (in_array($category, ['cyclone', 'flood', 'storm', 'shelter', 'health', 'rescue', 'fire', 'earthquake', 'landslide', 'river_level', 'general'], true)) {
            $query->where('category', $category);
        }

        if (in_array($severity, ['critical', 'high', 'moderate'], true)) {
            $query->where('severity', $severity);
        }

        if (in_array($status, ['active', 'resolved'], true)) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('affected_area', 'like', "%{$search}%");
            });
        }

        $alerts = $query->paginate(5)->withQueryString();

        return view('alerts.dashboard-index', compact('alerts', 'totalAlerts', 'activeAlerts', 'resolvedAlerts', 'lastUpdated', 'category', 'severity', 'status', 'search'));
    }

    public function show(DisasterAlert $alert): View
    {
        return view('alerts.show', compact('alert'));
    }

    public function create(): View
    {
        return view('alerts.create', ['alert' => new DisasterAlert()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $alert = DisasterAlert::create($this->validatedData($request));

        return redirect()->route('alerts.show', $alert)->with('status', 'News or alert published.');
    }

    public function edit(DisasterAlert $alert): View
    {
        return view('alerts.edit', compact('alert'));
    }

    public function update(Request $request, DisasterAlert $alert): RedirectResponse
    {
        $alert->update($this->validatedData($request));

        return redirect()->route('alerts.show', $alert)->with('status', 'News or alert updated.');
    }

    public function destroy(DisasterAlert $alert): RedirectResponse
    {
        $alert->delete();

        return redirect()->route('alerts.index')->with('status', 'News or alert deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'kind' => ['required', 'in:news,alert'],
            'category' => ['required', 'in:cyclone,flood,storm,shelter,health,rescue,fire,earthquake,landslide,river_level,general'],
            'severity' => ['required', 'in:critical,high,moderate'],
            'location' => ['required', 'string', 'max:160'],
            'affected_area' => ['required', 'string', 'max:180'],
            'summary' => ['required', 'string', 'max:280'],
            'body' => ['required', 'string', 'max:20000'],
            'safety_instructions' => ['required', 'string', 'max:12000'],
            'status' => ['required', 'in:active,resolved'],
        ]);
    }
}
