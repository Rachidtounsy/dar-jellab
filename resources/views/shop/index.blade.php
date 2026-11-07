@extends('layouts.app')
@section('title', $pageTitle)
@php
  $segCats = !empty($f['segment']) ? ($navTree[$f['segment']] ?? collect()) : collect();
  $facetKeys = ['color', 'fabric', 'stitch', 'occasion', 'min', 'max'];
  $active = array_intersect_key($f, array_flip($facetKeys));
  $without = fn ($k) => url(($k === 'occasion' && str_starts_with($path, 'occasion/')) ? 'shop' : $path).(($q = http_build_query(array_diff_key(array_diff_key($f, ['segment' => 1, 'cat' => 1, 'accessory' => 1, 'sale' => 1]), [$k => 1]))) ? '?'.$q : '');
  $sorts = ['new' => 'sort_new', 'popular' => 'sort_popular', 'price_asc' => 'sort_price_asc', 'price_desc' => 'sort_price_desc'];
  $termName = fn ($type, $slug) => optional(\App\Models\Term::where('type', $type)->where('slug', $slug)->first())->name ?? $slug;
@endphp
@section('content')
<section class="wrap pad">
  <nav class="crumbs"><a href="{{ url('/') }}">{{ t('home') }}</a> / <a href="{{ url('shop') }}">{{ t('shop') }}</a>@if (!empty($f['segment'])) / <a href="{{ url('shop/'.$f['segment']) }}">{{ segment_label($f['segment']) }}</a>@endif</nav>
  <div class="sec-h"><h1>{{ $pageTitle }}</h1><span class="muted">{{ $products->total() }} {{ t('items') }}</span></div>
  @if ($segCats->isNotEmpty())
    <div class="chips"><a class="chip {{ empty($f['cat']) ? 'on' : '' }}" href="{{ url('shop/'.$f['segment']) }}">{{ t('all') }}</a>
      @foreach ($segCats as $c)<a class="chip {{ ($f['cat'] ?? '') === $c->slug ? 'on' : '' }}" href="{{ url('shop/'.$f['segment'].'/'.$c->slug) }}">{{ $c->name }}</a>@endforeach</div>
  @endif

  <div class="shop">
    <button class="btn btn-ghost fbtn" data-open="filters">{{ t('filters') }}{{ $active ? ' ('.count($active).')' : '' }}</button>
    <aside class="filters" id="filters">
      <div class="filters-h"><b>{{ t('filters') }}</b><button class="icon-btn" data-close="filters" aria-label="×"><svg viewBox="0 0 24 24"><path d="M5 5l14 14M19 5 5 19"/></svg></button></div>
      <form method="get" action="{{ url($path) }}">
        @if (!empty($f['q']))<input type="hidden" name="q" value="{{ $f['q'] }}">@endif
        @if (!empty($f['sort']))<input type="hidden" name="sort" value="{{ $f['sort'] }}">@endif
        <details open><summary>{{ t('f_color') }}</summary><div class="f-sw">
          @foreach (\App\Models\Term::ofType('color')->get() as $c)<label title="{{ $c->name }}"><input type="radio" name="color" value="{{ $c->slug }}" @checked(($f['color'] ?? '') === $c->slug)><i style="--c:{{ $c->hex }}"></i></label>@endforeach</div></details>
        @foreach (['fabric' => 'f_fabric', 'stitch' => 'f_stitch', 'occasion' => 'f_occasion'] as $type => $lbl)
          @continue($type === 'occasion' && str_starts_with($path, 'occasion/'))
          <details @if(isset($f[$type])) open @endif><summary>{{ t($lbl) }}</summary><div class="f-list">
            <label><input type="radio" name="{{ $type }}" value="" @checked(empty($f[$type]))> {{ t('all') }}</label>
            @foreach (\App\Models\Term::ofType($type)->get() as $x)<label><input type="radio" name="{{ $type }}" value="{{ $x->slug }}" @checked(($f[$type] ?? '') === $x->slug)> {{ $x->name }}</label>@endforeach</div></details>
        @endforeach
        <details open><summary>{{ t('f_price') }}</summary><div class="f-price"><input type="number" name="min" min="0" placeholder="{{ t('min') }}" value="{{ $f['min'] ?? '' }}"><span>—</span><input type="number" name="max" min="0" placeholder="{{ t('max') }}" value="{{ $f['max'] ?? '' }}"></div></details>
        <div class="f-act"><button class="btn">{{ t('apply') }}</button>@if ($active)<a class="btn btn-ghost" href="{{ url($path) }}">{{ t('reset') }}</a>@endif</div>
      </form>
    </aside>
    <div class="results">
      <div class="toolbar">
        <div class="tags">@foreach ($active as $k => $v)
          <a class="tag" href="{{ $without($k) }}">{{ in_array($k, ['color', 'fabric', 'stitch', 'occasion']) ? $termName($k, $v) : t($k).' '.$v }} ×</a>@endforeach</div>
        <form method="get" class="sort">
          @foreach ($f as $k => $v)@continue(in_array($k, ['sort', 'segment', 'cat', 'accessory', 'sale']))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
          <select name="sort" onchange="this.form.submit()" aria-label="{{ t('sort') }}">@foreach ($sorts as $k => $l)<option value="{{ $k }}" @selected(($f['sort'] ?? 'new') === $k)>{{ t($l) }}</option>@endforeach</select></form>
      </div>
      @if ($products->isEmpty())
        <div class="empty"><p>{{ t('no_results') }}</p><a class="btn" href="{{ url('shop') }}">{{ t('view_all') }}</a></div>
      @else
        <div class="grid g3">@foreach ($products as $p)@include('partials.card', ['p' => $p])@endforeach</div>
      @endif
      @if ($products->lastPage() > 1)
        <nav class="pager">@for ($i = 1; $i <= $products->lastPage(); $i++)<a class="{{ $i === $products->currentPage() ? 'on' : '' }}" href="{{ $products->url($i) }}">{{ $i }}</a>@endfor</nav>
      @endif
    </div>
  </div>
</section>
@endsection
