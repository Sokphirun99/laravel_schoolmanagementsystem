@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $title ?? 'Feature Under Development' }}</h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-tools fa-4x text-secondary"></i>
                    </div>
                    <h3>{{ $message ?? 'This feature is coming soon!' }}</h3>
                    <p class="text-muted">We're working hard to bring you this feature. Please check back later.</p>
                    <a href="{{ route('portal.dashboard') }}" class="btn btn-primary mt-3">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
