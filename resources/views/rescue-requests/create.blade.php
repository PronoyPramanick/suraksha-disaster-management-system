<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Request Rescue Assistance</h2></x-slot>
    <div class="py-10"><div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6"><p class="text-gray-600">Share where help is needed and how many people need assistance. Your request will be reviewed by the response team.</p></div>
        @if ($errors->any())<div class="mb-5 rounded-md bg-red-50 p-4 text-red-700"><p class="font-medium">Please correct the highlighted information.</p><ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('rescue-requests.store') }}" class="space-y-6 rounded-lg bg-white p-6 shadow-sm">@csrf
            <div class="grid gap-6 sm:grid-cols-2">
                <div><label for="contact_name" class="block text-sm font-medium text-gray-700">Contact name</label><input id="contact_name" name="contact_name" required maxlength="120" value="{{ old('contact_name', auth()->user()->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                <div><label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact phone</label><input id="contact_phone" name="contact_phone" type="tel" required maxlength="30" value="{{ old('contact_phone', auth()->user()->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            </div>
            <div><label for="location" class="block text-sm font-medium text-gray-700">Location (district, area, landmark)</label><input id="location" name="location" required maxlength="255" value="{{ old('location') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g. Companiganj, near the cyclone shelter"></div>
            <div class="grid gap-6 sm:grid-cols-2">
                <div><label for="people_count" class="block text-sm font-medium text-gray-700">People needing assistance</label><input id="people_count" name="people_count" type="number" min="1" max="5000" required value="{{ old('people_count', 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                <div><label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label><select id="urgency" name="urgency" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="Critical" @selected(old('urgency') === 'Critical')>Critical</option><option value="High" @selected(old('urgency', 'High') === 'High')>High</option><option value="Moderate" @selected(old('urgency') === 'Moderate')>Moderate</option></select></div>
            </div>
            <div><label for="description" class="block text-sm font-medium text-gray-700">Emergency description</label><textarea id="description" name="description" rows="5" maxlength="5000" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Describe the situation, hazards, and any urgent medical needs.">{{ old('description') }}</textarea></div>
            <div class="flex items-center gap-4"><button type="submit" class="rounded-md bg-red-700 px-5 py-2.5 font-semibold text-white hover:bg-red-800">Submit rescue request</button><a href="{{ route('rescue-requests.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a></div>
        </form>
    </div></div>
</x-app-layout>
