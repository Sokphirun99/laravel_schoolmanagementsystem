@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $announcement->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <a href="{{ route('portal.announcements.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Announcements
                        </a>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-4">
                                <small class="text-muted">
                                    Posted {{ $announcement->created_at->format('F d, Y') }} 
                                    ({{ $announcement->created_at->diffForHumans() }})
                                    @if($announcement->user)
                                        by {{ $announcement->user->name }}
                                    @endif
                                </small>
                            </div>
                            
                            <div class="announcement-content">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                            
                            @if($announcement->expiry_date)
                                <div class="mt-4">
                                    <span class="badge bg-secondary">
                                        Valid until {{ $announcement->expiry_date->format('F d, Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
