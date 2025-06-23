@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>{{ __('Create Notice') }}</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('notices.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="notice_type" class="form-label">{{ __('Notice Type') }} <span class="text-danger">*</span></label>
                                <select id="notice_type" class="form-select @error('notice_type') is-invalid @enderror" name="notice_type" required>
                                    <option value="">{{ __('Select Type') }}</option>
                                    <option value="general" {{ old('notice_type') == 'general' ? 'selected' : '' }}>{{ __('General') }}</option>
                                    <option value="exam" {{ old('notice_type') == 'exam' ? 'selected' : '' }}>{{ __('Exam') }}</option>
                                    <option value="event" {{ old('notice_type') == 'event' ? 'selected' : '' }}>{{ __('Event') }}</option>
                                    <option value="holiday" {{ old('notice_type') == 'holiday' ? 'selected' : '' }}>{{ __('Holiday') }}</option>
                                    <option value="other" {{ old('notice_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                </select>
                                @error('notice_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="target_audience" class="form-label">{{ __('Target Audience') }} <span class="text-danger">*</span></label>
                                <select id="target_audience" class="form-select @error('target_audience') is-invalid @enderror" name="target_audience" required>
                                    <option value="">{{ __('Select Audience') }}</option>
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>{{ __('Everyone') }}</option>
                                    <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>{{ __('Students') }}</option>
                                    <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>{{ __('Teachers') }}</option>
                                    <option value="parents" {{ old('target_audience') == 'parents' ? 'selected' : '' }}>{{ __('Parents') }}</option>
                                </select>
                                @error('target_audience')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="class_id" class="form-label">{{ __('Class (Optional)') }}</label>
                            <select id="class_id" class="form-select @error('class_id') is-invalid @enderror" name="class_id">
                                <option value="">{{ __('All Classes') }}</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Leave empty to target all classes') }}</small>
                            @error('class_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') ?? date('Y-m-d') }}" required>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">{{ __('End Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') ?? date('Y-m-d', strtotime('+1 week')) }}" required>
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">{{ __('Message') }} <span class="text-danger">*</span></label>
                            <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="6" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox" id="status" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    {{ __('Active') }}
                                </label>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('notices.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Create Notice') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
