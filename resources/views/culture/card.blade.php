<article class="card art">
  <a href="{{ route('culture.show', $a->slug) }}" class="card-img wide">@if ($a->cover_url)<img src="{{ $a->cover_url }}" alt="" loading="lazy">@else<span class="art-ph">@include('partials.mark')</span>@endif<span class="badge">{{ $a->topic_label }}</span></a>
  <div class="card-b"><h3><a href="{{ route('culture.show', $a->slug) }}">{{ $a->title }}</a></h3><p class="muted sm">{{ \Illuminate\Support\Str::limit($a->excerpt_text, 130) }}</p></div>
</article>
