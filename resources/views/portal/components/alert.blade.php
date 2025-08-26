{{-- Portal Alert Component --}}
@props([
    'type' => 'info', // info, success, warning, danger
    'title' => null,
    'dismissible' => false,
    'icon' => null,
    'className' => ''
])

@php
    $alertClasses = [
        'info' => 'alert-info',
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'danger' => 'alert-danger'
    ];
    
    $alertIcons = [
        'info' => 'voyager-info-circled',
        'success' => 'voyager-check',
        'warning' => 'voyager-warning',
        'danger' => 'voyager-x'
    ];
    
    $alertClass = $alertClasses[$type] ?? 'alert-info';
    $alertIcon = $icon ?? $alertIcons[$type] ?? 'voyager-info-circled';
@endphp

<div class="alert {{ $alertClass }} portal-alert {{ $className }} {{ $dismissible ? 'alert-dismissible' : '' }}" role="alert">
    @if($dismissible)
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
    
    <div class="d-flex align-items-start">
        <div class="portal-alert-icon me-3">
            <i class="{{ $alertIcon }}"></i>
        </div>
        <div class="portal-alert-content flex-grow-1">
            @if($title)
                <h4 class="alert-heading">{{ $title }}</h4>
            @endif
            <div class="portal-alert-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
