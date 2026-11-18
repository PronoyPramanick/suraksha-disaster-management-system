<?php

namespace App\Http\Controllers;

use App\Models\MissingPersonReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MissingPersonReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = MissingPersonReport::query()->latest();
        $search = trim((string) $request->query('q', ''));
        $reportType = $request->query('type');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('last_known_location', 'like', "%{$search}%");
            });
        }

        if (in_array($reportType, ['missing', 'found'], true)) {
            $query->where('report_type', $reportType);
        } else {
            $reportType = '';
        }

        return view('missing-persons.index', [
            'reports' => $query->paginate(12)->withQueryString(),
            'search' => $search,
            'reportType' => $reportType,
        ]);
    }

    public function create(Request $request): View
    {
        $reportType = $request->query('type', 'missing');
        $reportType = in_array($reportType, ['missing', 'found'], true) ? $reportType : 'missing';

        return view('missing-persons.create', compact('reportType'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'report_type' => ['required', Rule::in(['missing', 'found'])],
            'name' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'last_known_location' => ['required', 'string', 'max:255'],
            'last_seen_date' => ['required', 'date', 'before_or_equal:today'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg', 'max:2048'],
            'contact_method' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:3000'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('missing-persons', 'public');
        }

        unset($data['photo']);
        $data['user_id'] = $request->user()->id;
        $data['status'] = $data['report_type'] === 'found' ? 'Located' : 'Missing';

        MissingPersonReport::create($data);

        $message = $data['report_type'] === 'found'
            ? 'Found person information submitted.'
            : 'Missing person report submitted.';

        return redirect()->route('missing-persons.index')->with('status', $message);
    }

    public function update(Request $request, MissingPersonReport $missingPerson): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin' || $missingPerson->user_id === $request->user()->id, 403);

        $allowedStatuses = $missingPerson->report_type === 'found'
            ? ['Located', 'Closed']
            : ['Missing', 'Located', 'Closed'];

        $data = $request->validate([
            'status' => ['required', Rule::in($allowedStatuses)],
        ]);

        $missingPerson->update($data);

        return back()->with('status', 'Case status updated.');
    }
}
