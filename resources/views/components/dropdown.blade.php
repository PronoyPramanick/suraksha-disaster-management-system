@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1'])

<div class="dropdown position-relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">{{ $trigger }}</div>
    <div
        x-show="open"
        x-bind:class="open ? 'show' : ''"
        class="dropdown-menu {{ $align === 'right' ? 'dropdown-menu-end' : '' }}"
        style="display: none; min-width: {{ $width === '48' ? '12rem' : $width }};"
        @click="open = false"
    >
        {{ $content }}
    </div>
</div>
