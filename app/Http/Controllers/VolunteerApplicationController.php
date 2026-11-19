<?php

namespace App\Http\Controllers;

use App\Models\VolunteerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VolunteerApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $admin = $request->user()->role === 'admin';
        $applications = VolunteerApplication::query()
            ->with('user')
            ->when(! $admin, fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest()
            ->paginate(15);

        return view('volunteers.index', compact('applications', 'admin'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        abort_if($request->user()->role === 'admin', 403);
        abort_if($request->user()->role === 'volunteer', 403, 'Your volunteer application has already been approved.');

        if (VolunteerApplication::where('user_id', $request->user()->id)->exists()) {
            return redirect()->route('volunteers.index');
        }

        return view('volunteers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user()->role === 'admin' || $request->user()->role === 'volunteer', 403);
        abort_if(VolunteerApplication::where('user_id', $request->user()->id)->exists(), 403, 'You have already applied.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
            'blood_group' => ['required', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-,Unknown'],
            'skills' => ['required', 'string', 'max:2000'],
            'availability' => ['required', 'string', 'max:255'],
            'experience' => ['nullable', 'string', 'max:2000'],
        ]);

        $request->user()->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
        ]);
        $request->user()->volunteerApplication()->create($data);

        return redirect()->route('volunteers.index')->with('status', 'Application submitted. You will receive volunteer access after administrator approval.');
    }

    public function review(Request $request, VolunteerApplication $application): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $data = $request->validate([
            'status' => ['required', 'in:Approved,Rejected'],
        ]);

        DB::transaction(function () use ($application, $request, $data): void {
            $lockedApplication = VolunteerApplication::query()
                ->whereKey($application->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($lockedApplication->status === 'Pending', 422, 'This application has already been reviewed.');

            $lockedApplication->update([
                'status' => $data['status'],
                'reviewed_by' => $request->user()->id,
            ]);

            $user = $lockedApplication->user;
            $approved = $data['status'] === 'Approved';
            $volunteerId = $approved
                ? ($user->volunteer_id ?: 'SUR-VOL-'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT))
                : null;

            $user->update([
                'role' => $approved ? 'volunteer' : 'citizen',
                'volunteer_id' => $volunteerId,
            ]);
        });

        return back()->with('status', 'Volunteer application '.$data['status'].'.');
    }
}
