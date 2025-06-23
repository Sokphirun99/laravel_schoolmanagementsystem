@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    @if($announcements->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No announcements available.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($announcements as $announcement)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-1">{{ $announcement->title }}</h5>
                                        <small>{{ $announcement->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($announcement->content, 200) }}</p>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Posted {{ $announcement->created_at->diffForHumans() }}</small>
                                        <a href="{{ route('portal.announcements.show', $announcement) }}" class="btn btn-sm btn-primary">Read More</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $announcements->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
