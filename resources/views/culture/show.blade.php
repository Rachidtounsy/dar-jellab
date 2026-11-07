@extends('layouts.app')
@section('title', $a->title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($a->excerpt_text ?: $a->body_html), 150))
@push('og')@if ($a->cover_url)<meta property="og:image" content="{{ $a->cover_url }}">@endif<meta property="og:type" content="article">@endpush
@section('content')
<article class="wrap pad narrow">
  <nav class="crumbs"><a href="{{ url('/') }}">{{ t('home') }}</a> / <a href="{{ route('culture') }}">{{ t('culture') }}</a></nav>
  <span class="chip sm">{{ $a->topic_label }}</span>
  <h1>{{ $a->title }}</h1>
  @if ($a->cover_url)<img class="cover" src="{{ $a->cover_url }}" alt="">@endif
  <div class="prose">{!! $a->body_html !!}</div>
</article>
@if ($products->isNotEmpty())
<section class="wrap sec"><div class="sec-h"><h2>{{ t('shop_the_occasion') }}</h2><a href="{{ url('occasion/'.$occ) }}">{{ t('view_all') }} ←</a></div>
  <div class="grid">@foreach ($products as $p)@include('partials.card', ['p' => $p])@endforeach</div></section>
@endif
@if ($more->isNotEmpty())
<section class="wrap sec"><div class="sec-h"><h2>{{ t('more_articles') }}</h2></div><div class="grid g3">@foreach ($more as $m)@include('culture.card', ['a' => $m])@endforeach</div></section>
@endif
@endsection
