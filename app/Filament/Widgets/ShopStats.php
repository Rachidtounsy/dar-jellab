<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Variant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShopStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $valid = fn () => Order::whereNotIn('status', ['cancelled', 'returned']);
        $month = now()->startOfMonth();

        return [
            Stat::make('طلبات جديدة', Order::where('status', 'new')->count())->description('تنتظر التأكيد')->color('danger'),
            Stat::make('طلبات اليوم', Order::whereDate('created_at', today())->count())->description('هاد الشهر: '.$valid()->where('created_at', '>=', $month)->count()),
            Stat::make('مبيعات الشهر', number_format($valid()->where('created_at', '>=', $month)->sum('total'), 0, '.', ' ').' د.م')->color('success'),
            Stat::make('مخزون ناقص', Variant::where('stock', '<=', 1)->whereHas('product', fn ($q) => $q->where('active', true))->count())->description('أسطر بمخزون 0 أو 1')->color('warning'),
        ];
    }
}
