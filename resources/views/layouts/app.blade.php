@php
  $name = setting_l('site_name');
  $social = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'telegram' => 'Telegram', 'snapchat' => 'Snapchat', 'pinterest' => 'Pinterest', 'x' => 'X'];
  $other = app()->getLocale() === 'ar' ? 'fr' : 'ar';
  $pageTitle = trim($__env->yieldContent('title')) ?: $name;
@endphp
<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $pageTitle }}{{ $pageTitle !== $name ? ' | '.$name : '' }}</title>
<meta name="description" content="{{ trim($__env->yieldContent('description')) ?: setting_l('tagline') }}"><meta name="theme-color" content="#1F3A5F">
<meta property="og:site_name" content="{{ $name }}"><meta property="og:title" content="{{ $pageTitle }}"><meta property="og:type" content="website"><meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_MA' : 'fr_MA' }}">
@stack('og')
<link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">
<link rel="preload" href="{{ asset('fonts/tajawal-arabic-500-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@if ($px = setting('meta_pixel'))
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $px }}');fbq('track','PageView');</script>
@endif
@stack('head')
</head>
<body>
@if ($a = setting_l('announce'))<div class="announce">{{ $a }}</div>@endif
<header class="hdr">
  <div class="wrap hdr-in">
    <button class="icon-btn burger" data-open="drawer" aria-label="{{ t('menu') }}"><svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg></button>
    @include('partials.logo')
    <nav class="nav" aria-label="main">
      @foreach (['men', 'women', 'boys', 'girls'] as $s)
        @continue(empty($navTree[$s]))
        <div class="nav-i"><a href="{{ url('shop/'.$s) }}">{{ segment_label($s) }}</a>
          <div class="mega"><div class="mega-in">
            @foreach ($navTree[$s] as $c)<a href="{{ url('shop/'.$s.'/'.$c->slug) }}">{{ $c->name }}</a>@endforeach
            <a class="all" href="{{ url('shop/'.$s) }}">{{ t('view_all') }} ←</a>
          </div></div></div>
      @endforeach
      <div class="nav-i"><a href="{{ url('accessories') }}">{{ t('accessories') }}</a></div>
      <div class="nav-i"><a href="#">{{ t('occasions') }}</a>
        <div class="mega"><div class="mega-in">@foreach ($occasions as $o)<a href="{{ url('occasion/'.$o->slug) }}">{{ $o->name }}</a>@endforeach</div></div></div>
      <div class="nav-i"><a href="{{ url('culture') }}">{{ t('culture') }}</a></div>
      <div class="nav-i"><a href="{{ url('sale') }}" class="hot">{{ t('sale') }}</a></div>
    </nav>
    <form class="search" action="{{ url('search') }}" role="search"><input name="q" value="{{ request('q') }}" placeholder="{{ t('search_ph') }}" aria-label="{{ t('search') }}"><button aria-label="{{ t('search') }}"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></button></form>
    <div class="hdr-act">
      <a class="cart" href="{{ route('cart') }}" aria-label="{{ t('cart') }}"><svg viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6zM9 8a3 3 0 0 1 6 0"/></svg>@if ($cartCount)<b>{{ $cartCount }}</b>@endif</a>
      <a class="lang" href="{{ url('lang/'.$other) }}">{{ $other === 'fr' ? 'FR' : 'عربية' }}</a>
      @if (wa_number())<a class="btn btn-wa sm" href="{{ wa_link(t('wa_hello')) }}" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Z"/></svg><span>{{ t('whatsapp') }}</span></a>@endif
    </div>
  </div>
</header>

<div class="drawer" id="drawer" hidden>
  <div class="drawer-bg" data-close="drawer"></div>
  <aside class="drawer-p">
    <div class="drawer-h">@include('partials.logo')<button class="icon-btn" data-close="drawer" aria-label="×"><svg viewBox="0 0 24 24"><path d="M5 5l14 14M19 5 5 19"/></svg></button></div>
    @foreach (['men', 'women', 'boys', 'girls'] as $s)
      @continue(empty($navTree[$s]))
      <details><summary>{{ segment_label($s) }}</summary>
        @foreach ($navTree[$s] as $c)<a href="{{ url('shop/'.$s.'/'.$c->slug) }}">{{ $c->name }}</a>@endforeach
        <a class="all" href="{{ url('shop/'.$s) }}">{{ t('view_all') }}</a></details>
    @endforeach
    <details><summary>{{ t('occasions') }}</summary>@foreach ($occasions as $o)<a href="{{ url('occasion/'.$o->slug) }}">{{ $o->name }}</a>@endforeach</details>
    <a class="d-link" href="{{ url('accessories') }}">{{ t('accessories') }}</a>
    <a class="d-link hot" href="{{ url('sale') }}">{{ t('sale') }}</a>
    <a class="d-link" href="{{ url('culture') }}">{{ t('culture') }}</a>
    <a class="d-link" href="{{ route('size-guide') }}">{{ t('size_guide') }}</a>
    <a class="d-link" href="{{ url('about') }}">{{ t('page_about') }}</a>
    <a class="d-link" href="{{ url('contact') }}">{{ t('page_contact') }}</a>
    <a class="d-link" href="{{ url('lang/'.$other) }}">{{ $other === 'fr' ? 'Français' : 'العربية' }}</a>
  </aside>
</div>

<main id="main">
@if (session('ok'))<div class="wrap"><div class="flash ok">{{ session('ok') }}</div></div>@endif
@if (session('error'))<div class="wrap"><div class="flash err">{{ session('error') }}</div></div>@endif
@yield('content')</main>

<footer class="ftr">
  <div class="wrap ftr-g">
    <div>@include('partials.logo')<p class="muted">{{ setting_l('about') }}</p>
      <div class="social">@foreach ($social as $k => $lbl)@if ($u = setting($k))<a href="{{ $u }}" target="_blank" rel="noopener" aria-label="{{ $lbl }}">{{ $lbl }}</a>@endif @endforeach</div></div>
    <div><h4>{{ t('shop') }}</h4>@foreach (['men', 'women', 'boys', 'girls'] as $s)<a href="{{ url('shop/'.$s) }}">{{ segment_label($s) }}</a>@endforeach<a href="{{ url('accessories') }}">{{ t('accessories') }}</a><a href="{{ url('sale') }}">{{ t('sale') }}</a></div>
    <div><h4>{{ t('info') }}</h4><a href="{{ url('culture') }}">{{ t('culture') }}</a><a href="{{ route('size-guide') }}">{{ t('size_guide') }}</a><a href="{{ url('about') }}">{{ t('page_about') }}</a><a href="{{ url('delivery') }}">{{ t('page_delivery') }}</a><a href="{{ url('contact') }}">{{ t('page_contact') }}</a></div>
    <div><h4>{{ t('page_contact') }}</h4>
      @foreach (['whatsapp1', 'whatsapp2'] as $k)@if ($n = setting($k))<a href="{{ wa_link('', $n) }}" target="_blank" rel="noopener" dir="ltr">WhatsApp: {{ $n }}</a>@endif @endforeach
      @if ($x = setting('phone'))<a href="tel:{{ preg_replace('/[^\d+]/', '', $x) }}" dir="ltr">{{ $x }}</a>@endif
      @if ($x = setting('email'))<a href="mailto:{{ $x }}">{{ $x }}</a>@endif
      @if ($x = setting_l('address'))<p class="muted">{!! nl2br(e($x)) !!}</p>@endif
      @if ($x = setting_l('hours'))<p class="muted">{{ $x }}</p>@endif</div>
  </div>
  <div class="wrap ftr-b"><span>© {{ date('Y') }} {{ $name }}</span><span class="muted">{{ t('cod_note') }}</span></div>
</footer>

<nav class="bar" aria-label="mobile">
  <a href="{{ url('/') }}"><svg viewBox="0 0 24 24"><path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>{{ t('home') }}</a>
  <button data-open="drawer"><svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h10"/></svg>{{ t('categories') }}</button>
  <a href="{{ url('search') }}"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>{{ t('search') }}</a>
  <a href="{{ route('cart') }}" class="bar-cart"><svg viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6zM9 8a3 3 0 0 1 6 0"/></svg>{{ t('cart') }}@if ($cartCount)<b>{{ $cartCount }}</b>@endif</a>
  @if (wa_number())<a class="wa" href="{{ wa_link(t('wa_hello')) }}" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Z"/></svg>{{ t('whatsapp') }}</a>@endif
</nav>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body></html>
