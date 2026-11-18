<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Volunteer Applications</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4">

        @if(!$admin && $applications->isEmpty())
            <a class="bg-blue-700 text-white px-4 py-2 rounded"
               href="{{ route('volunteers.create') }}">
                Apply to volunteer
            </a>
        @endif

        @if(session('status'))
            <p class="my-4 text-green-700">
                {{ session('status') }}
            </p>
        @endif

        <div class="grid gap-4 mt-6">

            @forelse($applications as $application)
                <div class="p-5 bg-white rounded shadow">

                    <h3 class="font-bold">
                        {{ $admin ? $application->user->name : 'Your application' }}:
                        {{ $application->status }}
                    </h3>

                    <p>Phone: {{ $application->phone }}</p>
                    <p>Skills: {{ $application->skills }}</p>
                    <p>Availability: {{ $application->availability }}</p>
                    <p>Experience: {{ $application->experience }}</p>

                    @if($admin && $application->status === 'Pending')
                        <form class="mt-3 flex gap-2"
                              method="POST"
                              action="{{ route('volunteers.review', $application) }}">
                            @csrf
                            @method('PATCH')

                            <button name="status" value="Approved"
                                    class="bg-green-700 text-white rounded px-4 py-2">
                                Approve
                            </button>

                            <button name="status" value="Rejected"
                                    class="bg-red-700 text-white rounded px-4 py-2">
                                Reject
                            </button>
                        </form>
                    @endif

                </div>
            @empty
                <p>No applications yet.</p>
            @endforelse

        </div>

        <div class="mt-5">
            {{ $applications->links() }}
        </div>

    </div>
</x-app-layout>