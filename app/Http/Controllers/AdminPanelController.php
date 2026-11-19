<?php

namespace App\Http\Controllers;

use App\Models\RescueRequest;
use App\Models\RescueRequestActivity;
use App\Models\User;
use Illuminate\View\View;

class AdminPanelController extends Controller
{
    public function index(): View
    {
        $members = User::query()
            ->whereIn('role', ['citizen', 'volunteer'])
            ->withCount(['rescueRequests', 'rescueRequestActivities'])
            ->latest()
            ->paginate(10, ['*'], 'members_page')
            ->withQueryString();

        $rescueRequests = RescueRequest::query()
            ->with(['user', 'activities.user'])
            ->latest('updated_at')
            ->paginate(10, ['*'], 'requests_page')
            ->withQueryString();

        return view('admin.panel', [
            'members' => $members,
            'rescueRequests' => $rescueRequests,
            'citizenCount' => User::where('role', 'citizen')->count(),
            'volunteerCount' => User::where('role', 'volunteer')->count(),
            'requestCount' => RescueRequest::count(),
            'pendingCount' => RescueRequest::where('status', 'Pending')->count(),
            'activities' => RescueRequestActivity::query()->with(['user', 'rescueRequest.user'])->latest()->paginate(12, ['*'], 'activity_page')->withQueryString(),
            'activityCount' => RescueRequestActivity::count(),
        ]);
    }
}
