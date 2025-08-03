{{-- Portal Table Component --}}
@props([
    'title' => null,
    'headers' => [],
    'data' => [],
    'showViewAll' => false,
    'viewAllUrl' => '#',
    'viewAllText' => 'View All',
    'emptyMessage' => 'No data available',
    'className' => ''
])

<div class="panel panel-bordered portal-table-panel {{ $className }}">
    @if($title)
        <div class="panel-heading d-flex justify-content-between align-items-center">
            <h3 class="panel-title">
                <i class="voyager-list"></i> {{ $title }}
            </h3>
            @if($showViewAll && count($data) > 0)
                <a href="{{ $viewAllUrl }}" class="btn btn-primary btn-sm">
                    {{ $viewAllText }}
                </a>
            @endif
        </div>
    @endif
    
    <div class="panel-body">
        @if(count($data) > 0)
            <div class="table-responsive">
                <table class="table table-hover portal-table">
                    @if(count($headers) > 0)
                        <thead>
                            <tr>
                                @foreach($headers as $header)
                                    <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        @else
            <div class="portal-empty-state text-center py-4">
                <i class="voyager-info-circled" style="font-size: 3rem; opacity: 0.5;"></i>
                <p class="mt-3 text-muted">{{ $emptyMessage }}</p>
            </div>
        @endif
    </div>
</div>
