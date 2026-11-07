@extends('layouts.app')
@section('title', $p->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags($p->localized('desc')), 150))
@php
  $gal = $p->images->isNotEmpty() ? $p->images->map(fn ($i) => ['full' => $i->url, 'th' => $i->url])->all() : [['full' => $p->image, 'th' => $p->image]];
  $desc = $p->localized('desc');
@endphp
@section('content')
<section class="wrap pad">
  <nav class="crumbs"><a href="{{ url('/') }}">{{ t('home') }}</a> / <a href="{{ url('shop/'.$p->segment) }}">{{ segment_label($p->segment) }}</a> / <a href="{{ url('shop/'.$p->segment.'/'.$p->category->slug) }}">{{ $p->category->name }}</a></nav>
  <div class="pdp">
    <div class="gal">
      <div class="gal-main"><img id="gmain" src="{{ $gal[0]['full'] }}" alt="{{ $p->name }}" width="600" height="800"></div>
      @if (count($gal) > 1)<div class="gal-th">@foreach ($gal as $i => $g)<button type="button" data-img="{{ $g['full'] }}" class="{{ $i ? '' : 'on' }}"><img src="{{ $g['th'] }}" alt="" loading="lazy"></button>@endforeach</div>@endif
    </div>
    <div class="info" id="pdp" data-name="{{ $p->name }}" data-price="{{ money($p->price) }}" data-url="{{ url('p/'.$p->slug) }}" data-wa="{{ wa_number() }}"
         data-l-color="{{ t('color') }}" data-l-size="{{ t('size') }}" data-l-price="{{ t('price') }}" data-l-hello="{{ t('wa_order_hello') }}" data-l-pick="{{ t('pick_options') }}" data-l-mtm="{{ t('wa_mtm') }}">
      <small class="muted">{{ segment_label($p->segment) }} · {{ $p->category->name }}</small>
      <h1>{{ $p->name }}</h1>
      <div class="price lg"><b>{{ money($p->price) }}</b>@if ($p->on_sale)<s>{{ money($p->old_price) }}</s><span class="badge badge-sale">-{{ $p->discount }}%</span>@endif</div>

      @if ($colors->isNotEmpty())
      <div class="opt"><b>{{ t('color') }}: <span id="cname"></span></b>
        <div class="f-sw lg">@foreach ($colors as $i => $c)<label title="{{ $c['name'] }}"><input type="radio" name="col" value="{{ $i }}" @checked($i === 0)><i style="--c:{{ $c['hex'] }}"></i></label>@endforeach</div></div>
      <div class="opt"><b>{{ t('size') }}</b><div class="sizes" id="sizes"></div></div>
      @endif
      <script type="application/json" id="cdata">{!! json_encode($colors, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>

      @if ($p->made_to_measure)<label class="mtm"><input type="checkbox" id="mtm"> <span><b>{{ t('made_to_measure') }}</b> — {{ t('mtm_note') }}</span></label>@endif

      <a id="order" class="btn btn-wa lg" href="#" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Z"/></svg>{{ t('order_wa') }}</a>
      <p class="muted sm">{{ t('cod_note') }}</p>
      <ul class="perks"><li>{{ t('v1_t') }}</li><li>{{ t('v2_t') }}</li><li>{{ t('v3_t') }}</li></ul>

      @if ($desc)<div class="desc"><h3>{{ t('description') }}</h3><p>{!! nl2br(e($desc)) !!}</p></div>@endif
      <table class="spec">
        @if ($p->fabric)<tr><th>{{ t('f_fabric') }}</th><td>{{ $p->fabric->name }}</td></tr>@endif
        @if ($p->stitch)<tr><th>{{ t('f_stitch') }}</th><td>{{ $p->stitch->name }}</td></tr>@endif
        @if ($p->occasions->isNotEmpty())<tr><th>{{ t('f_occasion') }}</th><td>@foreach ($p->occasions as $o)<a class="chip sm" href="{{ url('occasion/'.$o->slug) }}">{{ $o->name }}</a> @endforeach</td></tr>@endif
        <tr><th>{{ t('made_to_measure') }}</th><td>{{ $p->made_to_measure ? t('yes') : t('no') }}</td></tr>
      </table>
    </div>
  </div>
  @if ($related->isNotEmpty())<div class="sec"><div class="sec-h"><h2>{{ t('related') }}</h2></div><div class="grid">@foreach ($related as $x)@include('partials.card', ['p' => $x])@endforeach</div></div>@endif
</section>
@endsection
