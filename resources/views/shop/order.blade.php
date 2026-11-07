@extends('layouts.app')
@section('title', t('order_received'))
@push('head')@if (setting('meta_pixel') && session()->pull('track_purchase_'.$order->id, true) && $order->created_at->gt(now()->subMinutes(10)))<script>window.addEventListener('load',function(){if(window.fbq)fbq('track','Purchase',{value:{{ $order->total }},currency:'MAD'});});</script>@endif @endpush
@section('content')
@php($msg = $order->summary('السلام عليكم، هذا طلبي '.$order->number.' من الموقع:'))
<section class="wrap pad narrow center">
  <div class="ok-mark" aria-hidden="true">✓</div>
  <h1>{{ t('order_received') }}</h1>
  <p class="lead">{{ t('order_thanks', ['name' => $order->name]) }}</p>
  <p><b>{{ t('order_ref') }}:</b> <span dir="ltr">{{ $order->number }}</span></p>
  <div class="box-o">
    @foreach ($order->items as $i)<p><span>{{ $i->name }} @if ($i->color)· {{ $i->color }}@endif @if ($i->size)· {{ $i->size }}@endif @if ($i->made_to_measure)· {{ t('made_to_measure') }}@endif × {{ $i->qty }}</span><b>{{ money($i->price * $i->qty) }}</b></p>@endforeach
    <p><span>{{ t('shipping') }}</span><b>{{ $order->shipping ? money($order->shipping) : t('free') }}</b></p>
    <p class="tot"><span>{{ t('total') }}</span><b>{{ money($order->total) }}</b></p>
  </div>
  @if (wa_number())<a class="btn btn-wa lg" href="{{ wa_link($msg) }}" target="_blank" rel="noopener">{{ t('confirm_wa') }}</a>@endif
  <p><a href="{{ url('shop') }}">{{ t('continue_shopping') }}</a></p>
</section>
@endsection
