@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Messages</h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Conversations List -->
                        <div class="col-md-4 border-end">
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Recent Conversations</h6>
                                    <a href="{{ route('portal.communication.teachers') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus me-1"></i> New Message
                                    </a>
                                </div>
                                
                                <div class="list-group">
                                    @forelse($messages as $conversationId => $conversationMessages)
                                        @php
                                            $conversation = $conversationMessages->first();
                                            $otherUser = $conversation->sender_id == Auth::guard('portal')->id() ? 
                                                        $conversation->recipient : $conversation->sender;
                                            $unreadCount = $conversationMessages
                                                ->where('recipient_id', Auth::guard('portal')->id())
                                                ->whereNull('read_at')
                                                ->count();
                                        @endphp
                                        <a href="{{ route('portal.communication.conversation', $conversationId) }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                                  {{ request()->segment(3) == 'conversation' && request()->segment(4) == $conversationId ? 'active' : '' }}">
                                            <div>
                                                <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                                <small class="text-muted">
                                                    {{ Str::limit($conversationMessages->last()->message, 30) }}
                                                </small>
                                            </div>
                                            @if($unreadCount > 0)
                                                <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="text-center py-4">
                                            <p class="text-muted mb-0">No conversations yet</p>
                                            <a href="{{ route('portal.communication.teachers') }}" class="btn btn-sm btn-primary mt-3">
                                                Start a conversation
                                            </a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <!-- Placeholder for when no conversation is selected -->
                        <div class="col-md-8">
                            <div class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-comments fa-5x text-muted mb-4"></i>
                                    <h4>Your Messages</h4>
                                    <p class="text-muted">
                                        Select a conversation to view messages or start a new conversation.
                                    </p>
                                    <a href="{{ route('portal.communication.teachers') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus me-2"></i> Start New Conversation
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
