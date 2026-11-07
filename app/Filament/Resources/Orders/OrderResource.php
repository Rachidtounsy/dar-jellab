<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $modelLabel = 'طلب';

    protected static ?string $pluralModelLabel = 'الطلبات';

    protected static ?int $navigationSort = 0;

    public static function getNavigationBadge(): ?string
    {
        $n = Order::where('status', 'new')->count();

        return $n ? (string) $n : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('الطلب')->columns(3)->columnSpanFull()->schema([
                Select::make('status')->label('الحالة')->options(Order::STATUSES)->required(),
                TextInput::make('number')->label('رقم الطلب')->disabled()->dehydrated(false),
                TextInput::make('total')->label('الإجمالي (د.م)')->disabled()->dehydrated(false),
            ]),
            Section::make('الزبون')->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->label('الاسم')->required(),
                TextInput::make('phone')->label('الهاتف')->required()->extraInputAttributes(['dir' => 'ltr']),
                TextInput::make('city')->label('المدينة')->required(),
                Textarea::make('address')->label('العنوان')->rows(2),
                Textarea::make('notes')->label('ملاحظة الزبون')->rows(2)->disabled()->dehydrated(false),
                Textarea::make('admin_notes')->label('ملاحظة داخلية')->rows(2),
            ]),
            Section::make('المنتجات')->columnSpanFull()->schema([
                Repeater::make('items')->hiddenLabel()->relationship()->columns(5)->addable(false)->deletable(false)->reorderable(false)->schema([
                    TextInput::make('name')->label('المنتج')->disabled()->columnSpan(2),
                    TextInput::make('color')->label('اللون')->disabled(),
                    TextInput::make('size')->label('المقاس')->disabled(),
                    TextInput::make('qty')->label('الكمية')->disabled(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $colors = ['new' => 'danger', 'confirmed' => 'info', 'shipped' => 'warning', 'delivered' => 'success', 'cancelled' => 'gray', 'returned' => 'gray'];

        return $table
            ->columns([
                TextColumn::make('number')->label('الطلب')->state(fn (Order $o) => $o->number)->weight('bold')->searchable(query: fn ($query, string $search) => $query->where('id', (int) preg_replace('/\D/', '', $search))->orWhere('ref', 'like', "%$search%")),
                TextColumn::make('name')->label('الزبون')->searchable()->description(fn (Order $o) => $o->phone),
                TextColumn::make('city')->label('المدينة')->searchable(),
                TextColumn::make('total')->label('الإجمالي')->suffix(' د.م')->sortable(),
                TextColumn::make('status')->label('الحالة')->badge()->formatStateUsing(fn ($state) => Order::STATUSES[$state] ?? $state)->color(fn ($state) => $colors[$state] ?? 'gray'),
                TextColumn::make('created_at')->label('التاريخ')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([SelectFilter::make('status')->label('الحالة')->options(Order::STATUSES)])
            ->recordActions([
                Action::make('whatsapp')->label('واتساب')->icon(Heroicon::OutlinedChatBubbleLeftRight)->color('success')
                    ->url(fn (Order $o) => 'https://wa.me/'.$o->wa_phone.'?text='.rawurlencode('السلام عليكم '.$o->name.'، بخصوص طلبك '.$o->number.' من '.setting_l('site_name').'.'))->openUrlInNewTab(),
                Action::make('print')->label('طباعة')->icon(Heroicon::OutlinedPrinter)->url(fn (Order $o) => route('orders.print', $o))->openUrlInNewTab(),
                EditAction::make()->label('فتح'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('confirm')->label('تأكيد')->action(fn (Collection $r) => $r->each->update(['status' => 'confirmed']))->deselectRecordsAfterCompletion(),
                    BulkAction::make('ship')->label('تم الشحن')->action(fn (Collection $r) => $r->each->update(['status' => 'shipped']))->deselectRecordsAfterCompletion(),
                    BulkAction::make('deliver')->label('تم التسليم')->action(fn (Collection $r) => $r->each->update(['status' => 'delivered']))->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
