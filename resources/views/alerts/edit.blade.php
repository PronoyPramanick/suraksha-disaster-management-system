<x-app-layout>
    <x-slot name="header"><div class="alert-detail-heading"><a class="alert-back-link" href="{{ route('alerts.show', $alert) }}">← Back to update</a><h1>Edit update</h1></div></x-slot>
    <div class="alert-page-wrap"><section class="alert-form-card"><p class="alert-form-intro">Update the information visible to the community.</p>@include('alerts._form', ['alert' => $alert, 'editing' => true, 'action' => route('alerts.update', $alert)])</section></div>
</x-app-layout>
