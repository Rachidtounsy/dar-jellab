<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'آخر الطلبات';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(8))
            ->paginated(false)
            ->columns([
                TextColumn::make('number')->label('الطلب')->state(fn (Order $o) => $o->number),
                TextColumn::make('name')->label('الزبون'),
                TextColumn::make('city')->label('المدينة'),
                TextColumn::make('total')->label('الإجمالي')->suffix(' د.م'),
                TextColumn::make('status')->label('الحالة')->badge()->formatStateUsing(fn ($state) => Order::STATUSES[$state] ?? $state),
                TextColumn::make('created_at')->label('التاريخ')->since(),
            ])
            ->recordUrl(fn (Order $o) => OrderResource::getUrl('edit', ['record' => $o]));
    }
}
