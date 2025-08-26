@props([
    'title' => 'Stat',
    'value' => '0',
    'icon' => 'voyager-bar-chart',
    'color' => 'blue',
    'subtitle' => null,
    'trend' => null,
    'href' => null
])

@php
    $colorClasses = [
        'blue' => 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700',
        'green' => 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700',
        'purple' => 'from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700',
        'red' => 'from-red-500 to-red-600 hover:from-red-600 hover:to-red-700',
        'yellow' => 'from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700',
        'indigo' => 'from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700',
        'pink' => 'from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700',
        'teal' => 'from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700',
    ];
    
    $gradientClass = $colorClasses[$color] ?? $colorClasses['blue'];
    $isClickable = !empty($href);
@endphp

<div class="portal-stats-card-wrapper">
    @if($isClickable)
        <a href="{{ $href }}" class="block">
    @endif
    
    <div class="portal-stats-card bg-gradient-to-br {{ $gradientClass }} text-white p-6 rounded-2xl shadow-lg border-0 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <div class="p-2 bg-white/20 rounded-lg mr-3 group-hover:bg-white/30 transition-all duration-300">
                        <i class="{{ $icon }} text-xl"></i>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium uppercase tracking-wide">
                        {{ $title }}
                    </h3>
                </div>
                
                <div class="mb-3">
                    <span class="text-3xl font-bold text-white">
                        {{ $value }}
                    </span>
                    
                    @if($trend)
                        <span class="ml-2 text-sm {{ $trend['type'] === 'up' ? 'text-white/90' : 'text-white/70' }}">
                            <i class="voyager-{{ $trend['type'] === 'up' ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ $trend['value'] }}
                        </span>
                    @endif
                </div>
                
                @if($subtitle)
                    <p class="text-white/80 text-sm">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>
        
        @if($isClickable)
            <div class="mt-4 flex items-center text-white/80 text-sm group-hover:text-white transition-colors duration-300">
                <span>View details</span>
                <i class="voyager-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
            </div>
        @endif
    </div>
    
    @if($isClickable)
        </a>
    @endif
</div>

<style>
    .portal-stats-card-wrapper {
        height: 100%;
    }
    
    .portal-stats-card {
        height: 100%;
        min-height: 140px;
        position: relative;
        overflow: hidden;
    }
    
    .portal-stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30px, -30px);
        transition: all 0.3s ease;
    }
    
    .portal-stats-card:hover::before {
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    }
    
    @media (max-width: 768px) {
        .portal-stats-card {
            min-height: 120px;
        }
        
        .portal-stats-card .text-3xl {
            font-size: 1.875rem;
        }
    }
</style>

<style>
.stats-card {
    transition: all 0.3s ease;
    border-left: 4px solid #ddd;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.stats-card-link {
    text-decoration: none;
    color: inherit;
}

.stats-card-link:hover {
    text-decoration: none;
    color: inherit;
}

.stats-card-value {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
}

.stats-card-title {
    color: #666;
    font-size: 0.9rem;
}

.stats-card-icon {
    font-size: 2rem;
    opacity: 0.7;
}

.stats-card-success {
    border-left-color: #5cb85c;
}

.stats-card-success .stats-card-icon {
    color: #5cb85c;
}

.stats-card-warning {
    border-left-color: #f0ad4e;
}

.stats-card-warning .stats-card-icon {
    color: #f0ad4e;
}

.stats-card-info {
    border-left-color: #5bc0de;
}

.stats-card-info .stats-card-icon {
    color: #5bc0de;
}

.stats-card-danger {
    border-left-color: #d9534f;
}

.stats-card-danger .stats-card-icon {
    color: #d9534f;
}

.stats-card-primary {
    border-left-color: #337ab7;
}

.stats-card-primary .stats-card-icon {
    color: #337ab7;
}
</style>
