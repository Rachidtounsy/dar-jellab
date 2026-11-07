@extends('layouts.app')
@section('title', t('checkout'))
@section('content')
<section class="wrap pad">
  <h1>{{ t('checkout') }}</h1>
  <div class="cart-g">
    <form method="post" action="{{ route('checkout.place') }}" class="co">@csrf
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      @if ($errors->any())<div class="flash err">@foreach ($errors->all() as $e){{ $e }}<br>@endforeach</div>@endif
      <label>{{ t('full_name') }} *<input name="name" value="{{ old('name') }}" required autocomplete="name"></label>
      <label>{{ t('phone') }} *<input name="phone" value="{{ old('phone') }}" required inputmode="tel" autocomplete="tel" placeholder="06XXXXXXXX" dir="ltr"></label>
      <label>{{ t('city') }} *<input name="city" value="{{ old('city') }}" required autocomplete="address-level2"></label>
      <label>{{ t('address') }}<textarea name="address" rows="2" autocomplete="street-address">{{ old('address') }}</textarea></label>
      <label>{{ t('order_notes') }}<textarea name="notes" rows="2" placeholder="{{ t('order_notes_ph') }}">{{ old('notes') }}</textarea></label>
      <button class="btn lg">{{ t('place_order') }} — {{ money($subtotal + $shipping) }}</button>
      <small class="muted">{{ t('cod_note') }}</small>
    </form>
    <aside class="sum">
      <h3>{{ t('summary') }}</h3>
      @foreach ($lines as $l)<p><span>{{ $l['product']->name }} × {{ $l['qty'] }}<small class="muted"> {{ $l['color'] }} {{ $l['size'] }}</small></span><b>{{ money($l['total']) }}</b></p>@endforeach
      <p><span>{{ t('shipping') }}</span><b>{{ $shipping ? money($shipping) : t('free') }}</b></p>
      <p class="tot"><span>{{ t('total') }}</span><b>{{ money($subtotal + $shipping) }}</b></p>
    </aside>
  </div>
</section>
@endsection
