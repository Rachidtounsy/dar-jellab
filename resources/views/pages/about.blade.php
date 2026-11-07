@extends('layouts.app')
@section('title', t('page_about'))
@section('content')
<section class="wrap pad narrow"><h1>{{ t('page_about') }}</h1><p class="lead">{!! nl2br(e(setting_l('about'))) !!}</p>
<div class="vals in">@foreach ([1, 2, 3, 4] as $i)<div><b>{{ t("v{$i}_t") }}</b><span>{{ t("v{$i}_d") }}</span></div>@endforeach</div></section>
@endsection
