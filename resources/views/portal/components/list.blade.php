{{-- Portal List Component --}}
@props([
    'title' => null,
    'items' => [],
    'showViewAll' => false,
    'viewAllUrl' => '#',
    'viewAllText' => 'View All',
    'emptyMessage' => 'No items available',
    'className' => '',
    'showDividers' => true
])

<div class="panel panel-bordered portal-list-panel {{ $className }}">
    @if($title)
        <div class="panel-heading d-flex justify-content-between align-items-center">
            <h3 class="panel-title">
                <i class="voyager-list"></i> {{ $title }}
            </h3>
            @if($showViewAll && count($items) > 0)
                <a href="{{ $viewAllUrl }}" class="btn btn-primary btn-sm">
                    {{ $viewAllText }}
                </a>
            @endif
        </div>
    @endif
    
    <div class="panel-body">
        @if(count($items) > 0)
            <div class="portal-list {{ $showDividers ? 'with-dividers' : '' }}">
                {{ $slot }}
            </div>
        @else
            <div class="portal-empty-state text-center py-4">
                <i class="voyager-info-circled" style="font-size: 3rem; opacity: 0.5;"></i>
                <p class="mt-3 text-muted">{{ $emptyMessage }}</p>
            </div>
        @endif
    </div>
</div>
