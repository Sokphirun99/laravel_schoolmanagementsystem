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
                                    @forelse($messages->groupBy('conversation_id') as $convId => $conversationMessages)
                                        @php
                                            $conversation = $conversationMessages->first();
                                            $otherUser = $conversation->sender_id == Auth::guard('portal')->id() ? 
                                                        $conversation->recipient : $conversation->sender;
                                            $unreadCount = $conversationMessages
                                                ->where('recipient_id', Auth::guard('portal')->id())
                                                ->whereNull('read_at')
                                                ->count();
                                        @endphp
                                        <a href="{{ route('portal.communication.conversation', $convId) }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                                  {{ $convId == $conversationId ? 'active' : '' }}">
                                            <div>
                                                <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                                <small class="text-muted">
                                                    {{ Str::limit($conversationMessages->last()->message, 30) }}
                                                </small>
                                            </div>
                                            @if($unreadCount > 0 && $convId !== $conversationId)
                                                <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="text-center py-3">
                                            <p class="text-muted">No conversations yet</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <!-- Conversation Messages -->
                        <div class="col-md-8">
                            <div class="p-3 border-bottom">
                                @php 
                                    $otherUser = $messages->first()->sender_id == Auth::guard('portal')->id() ? 
                                                $messages->first()->recipient : $messages->first()->sender;
                                @endphp
                                <h5>{{ $otherUser->name }}</h5>
                                <span class="badge bg-secondary">{{ ucfirst($otherUser->roles->first()->name ?? 'User') }}</span>
                            </div>
                            
                            <div class="chat-messages p-3" style="height: 400px; overflow-y: auto;">
                                @foreach($messages as $message)
                                    <div class="message mb-3 {{ $message->sender_id == Auth::guard('portal')->id() ? 'text-end' : '' }}">
                                        <div class="message-content d-inline-block p-3 rounded 
                                            {{ $message->sender_id == Auth::guard('portal')->id() 
                                               ? 'bg-primary text-white' 
                                               : 'bg-light' }}" 
                                            style="max-width: 75%;">
                                            {{ $message->message }}
                                            <div class="message-time small {{ $message->sender_id == Auth::guard('portal')->id() ? 'text-white-50' : 'text-muted' }}">
                                                {{ $message->created_at->format('M d, h:i A') }}
                                                @if($message->sender_id == Auth::guard('portal')->id())
                                                    @if($message->read_at)
                                                        <i class="fas fa-check-double ms-1" title="Read"></i>
                                                    @else
                                                        <i class="fas fa-check ms-1" title="Sent"></i>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="message-input p-3 border-top">
                                <form action="{{ route('portal.communication.send', $conversationId) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="message" placeholder="Type your message..." required>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-paper-plane me-1"></i> Send
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto scroll to bottom of chat messages
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.querySelector('.chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });
</script>
@endpush
