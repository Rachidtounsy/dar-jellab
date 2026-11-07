@extends('layouts.app')
@section('title', t('cart'))
@section('content')
<section class="wrap pad">
  <h1>{{ t('cart') }}</h1>
  @if (empty($lines))
    <div class="empty"><p>{{ t('cart_empty') }}</p><a class="btn" href="{{ url('shop') }}">{{ t('continue_shopping') }}</a></div>
  @else
  <div class="cart-g">
    <form method="post" action="{{ route('cart.update') }}" class="cart-l">@csrf
      @foreach ($lines as $l)
        <div class="cl {{ $l['low'] ? 'low' : '' }}">
          <a href="{{ route('product', $l['product']->slug) }}"><img src="{{ $l['product']->image }}" alt="" width="84" height="112"></a>
          <div class="cl-i"><a href="{{ route('product', $l['product']->slug) }}"><b>{{ $l['product']->name }}</b></a>
            <small class="muted">@if ($l['color']){{ $l['color'] }}@endif @if ($l['size']) · {{ t('size') }} {{ $l['size'] }}@endif @if ($l['mtm']) · {{ t('made_to_measure') }}@endif</small>
            @if ($l['low'])<small class="warn">{{ t('low_stock') }}</small>@endif
            <span class="price"><b>{{ money($l['price']) }}</b></span></div>
          <div class="cl-q"><input type="number" name="qty[{{ $l['key'] }}]" value="{{ $l['qty'] }}" min="0" max="10" aria-label="{{ t('qty') }}"><b>{{ money($l['total']) }}</b>
            <button class="x" type="submit" form="rm-{{ md5($l['key']) }}" aria-label="{{ t('remove') }}">×</button></div>
        </div>
      @endforeach
      <button class="btn btn-ghost sm">{{ t('update_cart') }}</button>
    </form>
    @foreach ($lines as $l)<form id="rm-{{ md5($l['key']) }}" method="post" action="{{ route('cart.remove') }}">@csrf<input type="hidden" name="key" value="{{ $l['key'] }}"></form>@endforeach
    <aside class="sum">
      <h3>{{ t('summary') }}</h3>
      <p><span>{{ t('subtotal') }}</span><b>{{ money($subtotal) }}</b></p>
      <p><span>{{ t('shipping') }}</span><b>{{ $shipping ? money($shipping) : t('free') }}</b></p>
      @if ((int) setting('free_shipping_min') > 0 && $shipping > 0)<small class="muted">{{ t('free_shipping_from', ['n' => money(setting('free_shipping_min'))]) }}</small>@endif
      <p class="tot"><span>{{ t('total') }}</span><b>{{ money($subtotal + $shipping) }}</b></p>
      <a class="btn lg" href="{{ route('checkout') }}">{{ t('checkout') }}</a>
      <small class="muted">{{ t('cod_note') }}</small>
    </aside>
  </div>
  @endif
</section>
@endsection
