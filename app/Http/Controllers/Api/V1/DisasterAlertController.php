<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DisasterAlert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DisasterAlertController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'category' => ['sometimes', 'in:cyclone,flood,storm,shelter,health,rescue,fire,earthquake,landslide,river_level,general'],
            'severity' => ['sometimes', 'in:critical,high,moderate'],
            'status' => ['sometimes', 'in:active,resolved'],
            'q' => ['sometimes', 'string', 'max:160'],
            'has_coordinates' => ['sometimes', 'boolean'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $query = DisasterAlert::query()->latest('published_at');

        foreach (['category', 'severity', 'status'] as $filter) {
            if (isset($filters[$filter])) {
                $query->where($filter, $filters[$filter]);
            }
        }

        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('affected_area', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('has_coordinates')) {
            $query->whereNotNull('latitude')->whereNotNull('longitude');
        }

        $alerts = $query->paginate($filters['per_page'] ?? 25);

        return response()->json([
            'data' => $alerts->getCollection()->map(fn (DisasterAlert $alert) => $this->alertData($alert)),
            'links' => [
                'first' => $alerts->url(1),
                'last' => $alerts->url($alerts->lastPage()),
                'prev' => $alerts->previousPageUrl(),
                'next' => $alerts->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $alerts->currentPage(),
                'last_page' => $alerts->lastPage(),
                'per_page' => $alerts->perPage(),
                'total' => $alerts->total(),
            ],
        ]);
    }

    public function show(DisasterAlert $alert): JsonResponse
    {
        return response()->json(['data' => $this->alertData($alert, true)]);
    }

    private function alertData(DisasterAlert $alert, bool $includeDetails = false): array
    {
        $data = [
            'id' => $alert->id,
            'title' => $alert->title,
            'kind' => $alert->kind,
            'category' => $alert->category,
            'severity' => $alert->severity,
            'status' => $alert->status,
            'location' => $alert->location,
            'affected_area' => $alert->affected_area,
            'latitude' => $alert->latitude,
            'longitude' => $alert->longitude,
            'summary' => $alert->summary,
            'published_at' => $alert->published_at?->toIso8601String(),
            'updated_at' => $alert->updated_at?->toIso8601String(),
        ];

        if ($includeDetails) {
            $data['body'] = $alert->body;
            $data['safety_instructions'] = $alert->safety_instructions;
        }

        return $data;
    }
}
