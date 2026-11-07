@extends('layouts.app')
@section('title', '404')
@section('content')<section class="wrap pad center"><h1>404</h1><p class="muted">{{ t('not_found') }}</p><a class="btn" href="{{ url('/') }}">{{ t('home') }}</a></section>@endsection
