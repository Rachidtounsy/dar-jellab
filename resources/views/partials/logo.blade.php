<a class="logo" href="{{ url('/') }}" aria-label="{{ setting_l('site_name') }}">
  @if (setting('logo'))
    <img src="{{ upload_url(setting('logo')) }}" alt="{{ setting_l('site_name') }}" height="44">
  @else
    @include('partials.mark')
    <span class="logo-t"><b>{{ setting_l('site_name') }}</b><small>{{ app()->getLocale() === 'ar' ? setting('site_name_fr', 'Dar Jellaba') : setting('site_name_ar', 'دار الجلابة') }}</small></span>
  @endif
</a>
