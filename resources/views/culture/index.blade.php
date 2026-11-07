@extends('layouts.app')
@section('title', t('culture'))
@section('description', t('culture_intro'))
@section('content')
<section class="wrap pad">
  <div class="sec-h"><h1>{{ t('culture') }}</h1></div>
  <p class="lead muted">{{ t('culture_intro') }}</p>
  <div class="chips"><a class="chip {{ $topic ? '' : 'on' }}" href="{{ route('culture') }}">{{ t('all') }}</a>
    @foreach (\App\Models\Article::TOPICS as $k => $_)<a class="chip {{ $topic === $k ? 'on' : '' }}" href="{{ route('culture', ['topic' => $k]) }}">{{ t('topic_'.$k) }}</a>@endforeach</div>
  @if ($articles->isEmpty())<div class="empty"><p>{{ t('no_results') }}</p></div>
  @else<div class="grid g3">@foreach ($articles as $a)@include('culture.card', ['a' => $a])@endforeach</div>@endif
  @if ($articles->lastPage() > 1)<nav class="pager">@for ($i = 1; $i <= $articles->lastPage(); $i++)<a class="{{ $i === $articles->currentPage() ? 'on' : '' }}" href="{{ $articles->url($i) }}">{{ $i }}</a>@endfor</nav>@endif
</section>
@endsection
