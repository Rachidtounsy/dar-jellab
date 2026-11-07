<article class="card">
  <a href="{{ route('product', $p->slug) }}" class="card-img">
    <img src="{{ $p->image }}" alt="{{ $p->name }}" loading="lazy" width="300" height="400">
    @if ($p->on_sale)<span class="badge badge-sale">-{{ $p->discount }}%</span>
    @elseif ($p->badge)<span class="badge">{{ t('badge_'.$p->badge) }}</span>@endif
    @if ($p->made_to_measure)<span class="badge badge-mtm">{{ t('made_to_measure') }}</span>@endif
  </a>
  <div class="card-b">
    <small class="muted">{{ $p->category->name }} · {{ segment_label($p->segment) }}</small>
    <h3><a href="{{ route('product', $p->slug) }}">{{ $p->name }}</a></h3>
    <div class="price"><b>{{ money($p->price) }}</b>@if ($p->on_sale)<s>{{ money($p->old_price) }}</s>@endif</div>
    @php($cols = $p->colorsList())
    @if ($cols->isNotEmpty())
      <div class="dots">@foreach ($cols->take(6) as $c)<i style="--c:{{ $c->hex }}" title="{{ $c->name }}"></i>@endforeach @if ($cols->count() > 6)<em>+{{ $cols->count() - 6 }}</em>@endif</div>
    @endif
  </div>
</article>
