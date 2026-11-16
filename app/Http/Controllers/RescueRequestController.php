<?php

namespace App\Http\Controllers;

use App\Models\RescueRequest as RescueRequestRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RescueRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = RescueRequestRecord::query()->with('user')->latest();

        if ($request->user()->role !== 'admin') {
            $query->where('user_id', $request->user()->id);
        }

        return view('rescue-requests.index', ['requests' => $query->paginate(10)]);
    }

    public function create(): View
    {
        return view('rescue-requests.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'people_count' => ['required', 'integer', 'min:1', 'max:5000'],
            'urgency' => ['required', 'in:Critical,High,Moderate'],
        ]);

        $rescueRequest = $request->user()->rescueRequests()->create($data);

        return redirect()->route('rescue-requests.index')->with([
            'status' => 'Rescue request submitted. Keep the reference number shown below to track it.',
            'rescue_request_reference' => $rescueRequest->id,
        ]);
    }

    public function updateStatus(Request $request, RescueRequestRecord $rescueRequest): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:Pending,In Progress,Rescued']]);
        $rescueRequest->update($data);

        return back()->with('status', 'Rescue request status updated.');
    }
}
