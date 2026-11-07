@extends('layouts.app')
@section('title', setting_l('site_name').' — '.setting_l('tagline'))
@section('content')
@php($segimg = ['men' => ['F5F2EA', 'hood'], 'women' => ['0F7B5F', 'caftan'], 'boys' => ['7FB2E5', 'hood'], 'girls' => ['E8A0B4', 'caftan']])
@php($cols = \App\Models\Term::ofType('color')->get())
<section class="hero">
  <div class="wrap hero-in">
    <div class="hero-t">
      <span class="eyebrow">{{ setting_l('site_name') }}</span>
      <h1>{{ setting_l('hero_title') }}</h1>
      <p>{{ setting_l('hero_text') }}</p>
      <div class="hero-cta"><a class="btn" href="{{ url('shop') }}">{{ t('shop_now') }}</a><a class="btn btn-ghost" href="{{ url('occasion/wedding') }}">{{ t('wedding_collection') }}</a></div>
    </div>
    <div class="hero-art" aria-hidden="true">
      @if (setting('hero_image'))<img src="{{ upload_url(setting('hero_image')) }}" alt="">
      @else
        @foreach ([['0F7B5F', 'caftan'], ['F5F2EA', 'hood'], ['B5532F', 'hood']] as $i => [$h, $k])<img class="a{{ $i }}" src="{{ route('placeholder', [$h, $k]) }}" alt="">@endforeach
      @endif
    </div>
  </div>
</section>

<section class="wrap sec">
  <div class="sec-h"><h2>{{ t('shop_by_who') }}</h2></div>
  <div class="tiles">
    @foreach (['men', 'women', 'boys', 'girls'] as $s)
      <a class="tile" href="{{ url('shop/'.$s) }}"><img src="{{ route('placeholder', $segimg[$s]) }}" alt="" loading="lazy"><span><b>{{ segment_label($s) }}</b><small>{{ (int) ($counts[$s] ?? 0) }} {{ t('items') }}</small></span></a>
    @endforeach
  </div>
</section>

<section class="wrap sec">
  <div class="sec-h"><h2>{{ t('shop_by_occasion') }}</h2></div>
  <div class="chips">@foreach ($occasions as $o)<a class="chip" href="{{ url('occasion/'.$o->slug) }}">{{ $o->name }}</a>@endforeach</div>
</section>

@if ($feat->isNotEmpty())
<section class="wrap sec">
  <div class="sec-h"><h2>{{ t('featured') }}</h2><a href="{{ url('shop?sort=popular') }}">{{ t('view_all') }} ←</a></div>
  <div class="grid">@foreach ($feat as $p)@include('partials.card', ['p' => $p])@endforeach</div>
</section>
@endif

<section class="wrap sec">
  <div class="sec-h"><h2>{{ t('shop_by_color') }}</h2></div>
  <div class="swatches">@foreach ($cols as $c)<a href="{{ url('shop?color='.$c->slug) }}" class="sw"><i style="--c:{{ $c->hex }}"></i><span>{{ $c->name }}</span></a>@endforeach</div>
</section>

@if ($new->isNotEmpty())
<section class="wrap sec">
  <div class="sec-h"><h2>{{ t('new_arrivals') }}</h2><a href="{{ url('shop') }}">{{ t('view_all') }} ←</a></div>
  <div class="grid">@foreach ($new as $p)@include('partials.card', ['p' => $p])@endforeach</div>
</section>
@endif

@if ($sale->isNotEmpty())
<section class="wrap sec">
  <div class="sec-h"><h2>{{ t('sale') }}</h2><a href="{{ url('sale') }}">{{ t('view_all') }} ←</a></div>
  <div class="grid">@foreach ($sale as $p)@include('partials.card', ['p' => $p])@endforeach</div>
</section>
@endif

@if ($articles->isNotEmpty())
<section class="wrap sec">
  <div class="sec-h"><h2>{{ t('culture') }}</h2><a href="{{ route('culture') }}">{{ t('view_all') }} ←</a></div>
  <div class="grid g3">@foreach ($articles as $a)@include('culture.card', ['a' => $a])@endforeach</div>
</section>
@endif

<section class="values"><div class="wrap vals">@foreach ([1, 2, 3, 4] as $i)<div><b>{{ t("v{$i}_t") }}</b><span>{{ t("v{$i}_d") }}</span></div>@endforeach</div></section>
@endsection
