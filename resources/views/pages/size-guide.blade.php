@extends('layouts.app')
@section('title', t('size_guide'))
@section('content')
<section class="wrap pad narrow">
  <h1>{{ t('size_guide') }}</h1>
  <p class="lead">{{ t('sg_intro') }}</p>
  <h2>{{ t('sg_how') }}</h2>
  <ul class="perks"><li>{{ t('sg_chest') }}</li><li>{{ t('sg_shoulder') }}</li><li>{{ t('sg_sleeve') }}</li><li>{{ t('sg_length') }}</li></ul>
  <h2>{{ t('seg_men') }} <small class="muted">({{ t('sg_approx') }})</small></h2>
  <div class="tbl-w"><table class="spec t"><tr><th>{{ t('size') }}</th><th>M</th><th>L</th><th>XL</th><th>XXL</th></tr>
    <tr><th>{{ t('sg_chest_cm') }}</th><td>100–104</td><td>106–110</td><td>112–116</td><td>118–124</td></tr>
    <tr><th>{{ t('sg_length_cm') }}</th><td>140</td><td>144</td><td>148</td><td>152</td></tr></table></div>
  <h2>{{ t('seg_women') }} <small class="muted">({{ t('sg_approx') }})</small></h2>
  <div class="tbl-w"><table class="spec t"><tr><th>{{ t('size') }}</th><th>S</th><th>M</th><th>L</th><th>XL</th></tr>
    <tr><th>{{ t('sg_bust_cm') }}</th><td>84–88</td><td>90–94</td><td>96–100</td><td>102–108</td></tr>
    <tr><th>{{ t('sg_length_cm') }}</th><td>135</td><td>137</td><td>139</td><td>141</td></tr></table></div>
  <h2>{{ t('seg_boys') }} / {{ t('seg_girls') }} <small class="muted">({{ t('sg_approx') }})</small></h2>
  <div class="tbl-w"><table class="spec t"><tr><th>{{ t('sg_age') }}</th><th>4</th><th>6</th><th>8</th><th>10</th><th>12</th></tr>
    <tr><th>{{ t('sg_height_cm') }}</th><td>104</td><td>116</td><td>128</td><td>140</td><td>152</td></tr></table></div>
  <p class="muted">{{ t('sg_note') }}</p>
  @if (wa_number())<a class="btn btn-wa" href="{{ wa_link(t('sg_wa')) }}" target="_blank" rel="noopener">{{ t('sg_ask') }}</a>@endif
</section>
@endsection
