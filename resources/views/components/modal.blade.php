@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
])

@php
    $modalSize = match ($maxWidth) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl', '2xl' => 'modal-xl',
        default => '',
    };
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            const selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)].filter(element => !element.hasAttribute('disabled'));
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[(this.focusables().indexOf(document.activeElement) + 1) % this.focusables().length] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[Math.max(0, this.focusables().indexOf(document.activeElement) - 1)] || this.lastFocusable() },
    }"
    x-init="$watch('show', value => {
        document.body.classList.toggle('overflow-hidden', value);
        if (value && $el.dataset.focusable === 'true') setTimeout(() => firstFocusable()?.focus(), 100);
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey ? prevFocusable()?.focus() : nextFocusable()?.focus()"
    data-focusable="{{ $attributes->has('focusable') ? 'true' : 'false' }}"
>
    <div x-show="show" class="modal-backdrop fade show" style="display: none;" x-on:click="show = false"></div>
    <div
        x-show="show"
        x-bind:class="show ? 'show d-block' : ''"
        x-on:click.self="show = false"
        class="modal fade"
        role="dialog"
        aria-modal="true"
        aria-label="{{ $name }}"
        style="display: {{ $show ? 'block' : 'none' }};"
    >
        <div class="modal-dialog modal-dialog-centered {{ $modalSize }} {{ $maxWidth === '2xl' ? 'suraksha-modal-wide' : '' }}">
            <div class="modal-content">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
