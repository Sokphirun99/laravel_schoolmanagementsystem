@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>{{ $notice->title }}</h5>
                    <span class="badge {{ $notice->statusBadgeClass() }}">
                        {{ $notice->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-{{ $notice->typeClass() }} mb-2">{{ ucfirst($notice->notice_type) }}</span>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-users me-2"></i>{{ __('Target Audience') }}</h6>
                            <p>
                                {{ ucfirst($notice->target_audience) }}
                                @if($notice->class_id)
                                    ({{ $notice->class->name ?? 'Class' }})
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar-alt me-2"></i>{{ __('Valid Period') }}</h6>
                            <p>{{ $notice->start_date->format('M d, Y') }} - {{ $notice->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-comment-alt me-2"></i>{{ __('Message') }}</h6>
                        <div class="card">
                            <div class="card-body bg-light">
                                {!! nl2br(e($notice->message)) !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4 text-muted">
                        <small>
                            <i class="fas fa-clock me-2"></i>{{ __('Created') }}: {{ $notice->created_at->format('M d, Y h:i A') }}
                            @if($notice->created_at != $notice->updated_at)
                                <br>
                                <i class="fas fa-edit me-2"></i>{{ __('Updated') }}: {{ $notice->updated_at->format('M d, Y h:i A') }}
                            @endif
                            <br>
                            <i class="fas fa-user me-2"></i>{{ __('Created by') }}: {{ $notice->creator->name ?? 'Unknown' }}
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('notices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Notices') }}
                        </a>
                        
                        <div>
                            @if(Auth::id() == $notice->created_by || Auth::user()->isAdmin())
                                <a href="{{ route('notices.edit', $notice) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>{{ __('Edit') }}
                                </a>
                                <form action="{{ route('notices.destroy', $notice) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this notice?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
