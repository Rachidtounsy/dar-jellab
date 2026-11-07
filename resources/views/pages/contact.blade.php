@extends('layouts.app')
@section('title', t('page_contact'))
@section('content')
<section class="wrap pad narrow"><h1>{{ t('page_contact') }}</h1>
<div class="contact">
@foreach (['whatsapp1', 'whatsapp2'] as $k)@if ($n = setting($k))<a class="btn btn-wa" href="{{ wa_link(t('wa_hello'), $n) }}" target="_blank" rel="noopener" dir="ltr">WhatsApp {{ $n }}</a>@endif @endforeach
@if ($x = setting('phone'))<p><b>{{ t('phone') }}:</b> <a href="tel:{{ preg_replace('/[^\d+]/', '', $x) }}" dir="ltr">{{ $x }}</a></p>@endif
@if ($x = setting('email'))<p><b>Email:</b> <a href="mailto:{{ $x }}">{{ $x }}</a></p>@endif
@if ($x = setting_l('address'))<p><b>{{ t('address') }}:</b><br>{!! nl2br(e($x)) !!}</p>@endif
@if ($x = setting('map_url'))<p><a href="{{ $x }}" target="_blank" rel="noopener">{{ t('map') }} ←</a></p>@endif
@if ($x = setting_l('hours'))<p><b>{{ t('hours') }}:</b> {{ $x }}</p>@endif
<div>@foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'telegram' => 'Telegram', 'snapchat' => 'Snapchat', 'pinterest' => 'Pinterest', 'x' => 'X'] as $k => $l)@if ($u = setting($k))<a class="chip" href="{{ $u }}" target="_blank" rel="noopener">{{ $l }}</a> @endif @endforeach</div>
</div></section>
@endsection
