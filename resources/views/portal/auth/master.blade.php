@extends('voyager::auth.master')

@section('pre_css')
    <link rel="stylesheet" href="{{ asset('css/voyager-ui/portal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/voyager-ui/portal-login.css') }}">
@endsection

@section('title', 'Portal Login - ' . config('app.name'))
