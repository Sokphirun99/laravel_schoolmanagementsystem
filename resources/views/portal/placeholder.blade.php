@extends('portal.layouts.modern')

@section('content')
<div class="portal-card">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $title ?? 'Coming Soon' }}</h2>
    <p class="text-gray-600 dark:text-gray-300">{{ $message ?? 'This feature is under construction.' }}</p>
</div>
@endsection
