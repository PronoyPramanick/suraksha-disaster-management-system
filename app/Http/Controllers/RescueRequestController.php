<?php

namespace App\Http\Controllers;

use App\Models\RescueRequest as RescueRequestRecord;
use App\Models\RescueRequestActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RescueRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = RescueRequestRecord::query()->with(['user', 'activities.user'])->latest();

        if ($request->user()->role === 'volunteer') {
            $query->whereHas('user', fn ($userQuery) => $userQuery->where('role', 'citizen'));
        } elseif ($request->user()->role !== 'admin') {
            $query->where('user_id', $request->user()->id);
        } elseif ($request->filled('member')) {
            $data = $request->validate(['member' => ['required', 'integer', 'exists:users,id']]);
            $query->where('user_id', $data['member']);
        }

        return view('rescue-requests.index', ['requests' => $query->paginate(10)]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()->role === 'citizen', 403);
        return view('rescue-requests.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'citizen', 403);
        $data = $request->validate([
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'people_count' => ['required', 'integer', 'min:1', 'max:5000'],
            'urgency' => ['required', 'in:Critical,High,Moderate'],
        ]);

        $rescueRequest = $request->user()->rescueRequests()->create($data);
        RescueRequestActivity::create([
            'rescue_request_id' => $rescueRequest->id,
            'user_id' => $request->user()->id,
            'action' => 'submitted',
            'to_status' => 'Pending',
            'note' => 'Rescue request submitted.',
        ]);

        return redirect()->route('rescue-requests.index')->with([
            'status' => 'Rescue request submitted. Keep the reference number shown below to track it.',
            'rescue_request_reference' => $rescueRequest->id,
        ]);
    }

    public function updateStatus(Request $request, RescueRequestRecord $rescueRequest): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'volunteer'], true), 403);
        if ($request->user()->role === 'volunteer') {
            abort_unless($rescueRequest->user()->where('role', 'citizen')->exists(), 403);
        }
        $data = $request->validate(['status' => ['required', 'in:Pending,In Progress,Rescued']]);
        $previousStatus = $rescueRequest->status;
        if ($previousStatus === $data['status']) {
            return back()->with('status', 'The request is already marked '.$data['status'].'.');
        }

        $rescueRequest->update($data);
        RescueRequestActivity::create([
            'rescue_request_id' => $rescueRequest->id,
            'user_id' => $request->user()->id,
            'action' => 'status_updated',
            'from_status' => $previousStatus,
            'to_status' => $data['status'],
            'note' => 'Status updated by '.$request->user()->role.'.',
        ]);

        return back()->with('status', 'Rescue request status updated.');
    }
}
