@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Contact Teachers</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <a href="{{ route('portal.communication.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Messages
                        </a>
                    </div>
                    
                    @if(Auth::guard('portal')->user()->user_type === 'parent')
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i> 
                            Select your child's teacher to start a conversation. You can discuss academic progress, behavior, or any other concerns.
                        </div>
                    @else
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i> 
                            Select your teacher to start a conversation. You can ask questions about assignments, lessons, or request help.
                        </div>
                    @endif
                    
                    <div class="row">
                        @foreach($teachers as $teacher)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        @if($teacher->avatar)
                                            <img src="{{ Voyager::image($teacher->avatar) }}" 
                                                 class="rounded-circle mb-3" 
                                                 width="80" 
                                                 height="80">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                                 style="width: 80px; height: 80px;">
                                                <span style="font-size: 32px;">{{ substr($teacher->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        
                                        <h5>{{ $teacher->name }}</h5>
                                        @if($teacher->teacher)
                                            <p class="text-muted mb-3">
                                                {{ $teacher->teacher->designation ?? 'Teacher' }}
                                                @if($teacher->teacher->subject_id)
                                                    <br>{{ $teacher->teacher->subject->name ?? '' }}
                                                @endif
                                            </p>
                                        @endif
                                        
                                        <form action="{{ route('portal.communication.send') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="recipient_id" value="{{ $teacher->id }}">
                                            @if(isset($studentId))
                                                <input type="hidden" name="student_id" value="{{ $studentId }}">
                                                <div class="alert alert-light border p-2 mb-2">
                                                    <small>Sending message regarding student ID: {{ $studentId }}</small>
                                                </div>
                                            @endif
                                            <div class="mb-3">
                                                <textarea name="message" class="form-control" rows="3" placeholder="Write your message..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-paper-plane me-2"></i> Send Message
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
