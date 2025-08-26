@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #66bb6a, #4caf50); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-news"></i> School Announcements
                        </h3>
                    </div>
                    <div class="panel-body">
                        @if($announcements->isEmpty())
                            <div class="text-center" style="padding: 60px 0;">
                                <i class="voyager-megaphone" style="font-size: 72px; color: #d1d5db; margin-bottom: 20px;"></i>
                                <h3 style="color: #62a8ea; margin-bottom: 10px;">No Announcements</h3>
                                <p style="color: #78909c;">There are no announcements available at the moment.</p>
                            </div>
                        @else
                            <div class="row">
                                @foreach($announcements as $announcement)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="panel widget center bgimage" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); background-size: cover; min-height: 250px;">
                                        <div class="dimmer"></div>
                                        <div class="panel-content" style="padding: 20px;">
                                            <div style="position: absolute; top: 15px; right: 15px;">
                                                <span class="label label-success">{{ $announcement->created_at->format('M d') }}</span>
                                            </div>
                                            <h4 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); margin-bottom: 15px; margin-top: 20px;">
                                                {{ $announcement->title }}
                                            </h4>
                                            <p style="color: #e0e0e0; margin-bottom: 20px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                                                {{ Str::limit($announcement->content, 120) }}
                                            </p>
                                            <div style="position: absolute; bottom: 15px; left: 20px; right: 20px;">
                                                <small style="color: #b0b0b0;">{{ $announcement->created_at->diffForHumans() }}</small>
                                                <a href="{{ route('portal.announcements.show', $announcement) }}" 
                                                   class="btn btn-primary btn-sm pull-right">
                                                    <i class="voyager-eye"></i> Read More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($announcements->hasPages())
                            <div style="margin-top: 30px; text-center;">
                                {{ $announcements->links() }}
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
