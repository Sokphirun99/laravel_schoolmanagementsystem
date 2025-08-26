<div class="panel widget" style="border-top: 3px solid #f44336;">
    <div class="panel-heading">
        <h3 class="panel-title">Recent Notices</h3>
    </div>
    <div class="panel-body">
        @if(count($notices) > 0)
            <ul class="list-group">
                @foreach($notices as $notice)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-10">
                                <a href="{{ route('voyager.notices.show', $notice->id) }}">
                                    <span class="badge bg-{{ $notice->typeClass() }}">{{ ucfirst($notice->notice_type) }}</span>
                                    {{ Str::limit($notice->title, 40) }}
                                </a>
                            </div>
                            <div class="col-xs-2 text-right">
                                <span class="badge {{ $notice->statusBadgeClass() }}">
                                    {{ $notice->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="text-center">
                <a href="{{ route('voyager.notices.index') }}" class="btn btn-sm btn-primary">View All Notices</a>
            </div>
        @else
            <p class="text-center">No recent notices found.</p>
        @endif
    </div>
</div>
