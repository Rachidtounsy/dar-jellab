@extends('layouts.app')
@section('title', t('page_delivery'))
@section('content')
<section class="wrap pad narrow"><h1>{{ t('page_delivery') }}</h1><p class="lead">{!! nl2br(e(setting_l('delivery'))) !!}</p><p>{{ t('cod_note') }}</p><p>{{ t('v3_d') }}</p></section>
@endsection
