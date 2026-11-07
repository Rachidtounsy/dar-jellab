<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    public const SEGMENTS = ['men' => 'رجال', 'women' => 'نساء', 'boys' => 'أولاد', 'girls' => 'بنات'];

    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $modelLabel = 'صنف';

    protected static ?string $pluralModelLabel = 'الأصناف';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('segment')->label('الفئة')->options(self::SEGMENTS)->required(),
            TextInput::make('name_ar')->label('الاسم (عربية)')->required()->maxLength(120),
            TextInput::make('name_fr')->label('الاسم (فرنسية)')->maxLength(120),
            Toggle::make('accessory')->label('إكسسوار (بلغة، طربوش...)'),
            TextInput::make('sort')->label('الترتيب')->numeric()->default(0),
            Toggle::make('active')->label('مفعل')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('segment')->label('الفئة')->badge()->formatStateUsing(fn ($state) => self::SEGMENTS[$state] ?? $state)->sortable(),
                TextColumn::make('name_ar')->label('عربية')->searchable(),
                TextColumn::make('name_fr')->label('Français')->searchable(),
                TextColumn::make('products_count')->label('منتجات')->counts('products'),
                ToggleColumn::make('accessory')->label('إكسسوار'),
                ToggleColumn::make('active')->label('مفعل'),
                TextColumn::make('sort')->label('الترتيب')->sortable(),
            ])
            ->defaultSort('segment')
            ->reorderable('sort')
            ->filters([SelectFilter::make('segment')->label('الفئة')->options(self::SEGMENTS)])
            ->recordActions([EditAction::make(), DeleteAction::make()->before(function (DeleteAction $a, Category $record) {
                if ($record->products()->exists()) {
                    Notification::make()->danger()->title('الصنف فيه منتجات، عطله بدل الحذف.')->send();
                    $a->cancel();
                }
            })]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
