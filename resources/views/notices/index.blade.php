@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bullhorn me-2"></i>{{ __('Notices') }}</h2>
        @if(Auth::user()->isAdmin() || Auth::user()->isTeacher())
        <a href="{{ route('notices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>{{ __('Create Notice') }}
        </a>
        @endif
    </div>

    @if(Auth::user()->isAdmin() || Auth::user()->isTeacher())
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">{{ __('Filter Notices') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('notices.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="audience" class="form-label">{{ __('Target Audience') }}</label>
                    <select name="audience" id="audience" class="form-select">
                        <option value="">{{ __('All Audiences') }}</option>
                        <option value="all" {{ request('audience') === 'all' ? 'selected' : '' }}>{{ __('Everyone') }}</option>
                        <option value="students" {{ request('audience') === 'students' ? 'selected' : '' }}>{{ __('Students') }}</option>
                        <option value="teachers" {{ request('audience') === 'teachers' ? 'selected' : '' }}>{{ __('Teachers') }}</option>
                        <option value="parents" {{ request('audience') === 'parents' ? 'selected' : '' }}>{{ __('Parents') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="class_id" class="form-label">{{ __('Class') }}</label>
                    <select name="class_id" id="class_id" class="form-select">
                        <option value="">{{ __('All Classes') }}</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="show_all" class="form-label">{{ __('Status') }}</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="show_all" name="show_all" value="1" {{ request('show_all') ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_all">{{ __('Show Inactive') }}</label>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>{{ __('Apply Filter') }}
                    </button>
                    <a href="{{ route('notices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-2"></i>{{ __('Reset') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($notices->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>{{ __('No notices found.') }}
        </div>
    @else
        <div class="row">
            @foreach($notices as $notice)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 {{ $notice->status ? 'border-primary' : 'border-secondary opacity-75' }}">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-truncate" title="{{ $notice->title }}">
                                    {{ $notice->title }}
                                </h5>
                                <span class="badge {{ $notice->statusBadgeClass() }}">
                                    {{ $notice->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="badge bg-{{ $notice->typeClass() }} mb-2">{{ ucfirst($notice->notice_type) }}</span>
                                <p class="text-muted small">
                                    <i class="fas fa-users me-1"></i> 
                                    For: {{ ucfirst($notice->target_audience) }}
                                    @if($notice->class_id)
                                        ({{ $notice->class->name ?? 'Class' }})
                                    @endif
                                </p>
                                <p class="small mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $notice->start_date->format('M d, Y') }} - {{ $notice->end_date->format('M d, Y') }}
                                </p>
                            </div>
                            <p class="card-text">{{ Str::limit($notice->message, 100) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    {{ $notice->created_at->diffForHumans() }}
                                </small>
                                <div>
                                    <a href="{{ route('notices.show', $notice) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(Auth::id() == $notice->created_by || Auth::user()->isAdmin())
                                        <a href="{{ route('notices.edit', $notice) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('notices.destroy', $notice) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this notice?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $notices->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
