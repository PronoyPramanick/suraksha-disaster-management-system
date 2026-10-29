<x-app-layout>
    <x-slot name="header"><div class="alert-detail-heading"><a class="alert-back-link" href="{{ route('alerts.index') }}">← News &amp; Alerts</a><h1>Publish an update</h1></div></x-slot>
    <div class="alert-page-wrap"><section class="alert-form-card"><p class="alert-form-intro">Share a clear, verified update with the community.</p>@include('alerts._form', ['alert' => $alert, 'editing' => false, 'action' => route('alerts.store')])</section></div>
</x-app-layout>
