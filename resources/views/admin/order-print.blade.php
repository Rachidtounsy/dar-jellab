<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><title>{{ $order->number }}</title>
<style>body{font-family:Tahoma,Arial,sans-serif;max-width:720px;margin:24px auto;color:#111}h1{font-size:20px}table{width:100%;border-collapse:collapse;margin:14px 0}td,th{border:1px solid #bbb;padding:7px;text-align:start}.t{font-weight:700}@media print{button{display:none}}</style></head>
<body><button onclick="print()">طباعة</button>
<h1>{{ setting_l('site_name') }} — وصل الطلب {{ $order->number }}</h1>
<p>التاريخ: {{ $order->created_at->format('Y-m-d H:i') }}</p>
<p><b>الزبون:</b> {{ $order->name }} — <span dir="ltr">{{ $order->phone }}</span><br><b>المدينة:</b> {{ $order->city }}<br>@if ($order->address)<b>العنوان:</b> {{ $order->address }}<br>@endif @if ($order->notes)<b>ملاحظة:</b> {{ $order->notes }}@endif</p>
<table><tr><th>المنتج</th><th>اللون</th><th>المقاس</th><th>الكمية</th><th>الثمن</th></tr>
@foreach ($order->items as $i)<tr><td>{{ $i->name }}@if ($i->made_to_measure) (على المقاس)@endif</td><td>{{ $i->color }}</td><td>{{ $i->size }}</td><td>{{ $i->qty }}</td><td>{{ number_format($i->price * $i->qty, 0, '.', ' ') }} د.م</td></tr>@endforeach
<tr class="t"><td colspan="4">التوصيل</td><td>{{ $order->shipping ? number_format($order->shipping, 0, '.', ' ').' د.م' : 'مجاني' }}</td></tr>
<tr class="t"><td colspan="4">المبلغ المطلوب عند الاستلام</td><td>{{ number_format($order->total, 0, '.', ' ') }} د.م</td></tr></table></body></html>
