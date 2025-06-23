@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #26c6da, #00acc1); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-chat"></i> Messages & Communication
                        </h3>
                        <div class="panel-actions" style="margin-top: -5px;">
                            <a href="{{ route('portal.communication.teachers') }}" class="btn btn-primary btn-sm">
                                <i class="voyager-plus"></i> New Message
                            </a>
                        </div>
                    </div>
                    <div class="panel-body" style="padding: 0;">
                        <div class="row" style="margin: 0;">
                            <!-- Conversations List -->
                            <div class="col-md-4" style="padding: 0; border-right: 1px solid #f1f1f1;">
                                <div style="padding: 20px;">
                                    <h4 style="margin-bottom: 20px; color: #62a8ea;">
                                        <i class="voyager-list"></i> Recent Conversations
                                    </h4>
                                    
                                    <div class="list-group" style="border: none;">
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
                                           class="list-group-item list-group-item-action {{ request()->segment(3) == 'conversation' && request()->segment(4) == $conversationId ? 'active' : '' }}"
                                           style="border: none; border-radius: 8px; margin-bottom: 5px;">
                                            <div class="media">
                                                <div class="media-left" style="margin-right: 15px;">
                                                    <div class="avatar" style="background: linear-gradient(60deg, #26c6da, #00acc1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        {{ substr($otherUser->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h6 style="margin-bottom: 5px; color: #37474f;">{{ $otherUser->name }}</h6>
                                                    <p style="margin-bottom: 0; color: #78909c; font-size: 13px;">
                                                        {{ Str::limit($conversationMessages->last()->message, 30) }}
                                                    </p>
                                                </div>
                                                @if($unreadCount > 0)
                                                <div class="media-right">
                                                    <span class="label label-danger">{{ $unreadCount }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center" style="padding: 40px 0;">
                                            <i class="voyager-chat" style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;"></i>
                                            <p style="color: #78909c; margin-bottom: 15px;">No conversations yet</p>
                                            <a href="{{ route('portal.communication.teachers') }}" class="btn btn-primary btn-sm">
                                                <i class="voyager-plus"></i> Start a conversation
                                            </a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <!-- Placeholder for when no conversation is selected -->
                        <div class="col-md-8" style="padding: 0;">
                            <div class="text-center" style="padding: 80px 20px;">
                                <i class="voyager-chat" style="font-size: 120px; color: #d1d5db; margin-bottom: 30px;"></i>
                                <h2 style="color: #62a8ea; margin-bottom: 15px;">Your Messages</h2>
                                <p style="color: #78909c; margin-bottom: 30px; font-size: 16px;">
                                    Select a conversation to view messages or start a new conversation.
                                </p>
                                <a href="{{ route('portal.communication.teachers') }}" class="btn btn-primary">
                                    <i class="voyager-plus"></i> Start New Conversation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
