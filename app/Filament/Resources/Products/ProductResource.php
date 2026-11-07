<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\Term;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $modelLabel = 'منتج';

    protected static ?string $pluralModelLabel = 'المنتجات';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name_ar';

    private static function termOptions(string $type): array
    {
        return Term::where('type', $type)->where('active', true)->orderBy('sort')->get()->mapWithKeys(fn ($t) => [$t->id => $t->name_ar])->all();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('المعلومات الأساسية')->columns(2)->columnSpanFull()->schema([
                Select::make('category_id')->label('الصنف')->required()->searchable()
                    ->options(fn () => Category::orderBy('segment')->orderBy('sort')->get()->mapWithKeys(fn ($c) => [$c->id => CategoryResource::SEGMENTS[$c->segment].' — '.$c->name_ar])->all()),
                Select::make('badge')->label('الشارة')->options(Product::BADGES)->placeholder('بدون'),
                TextInput::make('name_ar')->label('الاسم (عربية)')->required()->maxLength(190),
                TextInput::make('name_fr')->label('الاسم (فرنسية)')->maxLength(190),
                TextInput::make('price')->label('الثمن (درهم)')->numeric()->required()->minValue(1),
                TextInput::make('old_price')->label('الثمن قبل التخفيض')->numeric()->minValue(0),
                Textarea::make('desc_ar')->label('الوصف (عربية)')->rows(3),
                Textarea::make('desc_fr')->label('الوصف (فرنسية)')->rows(3),
            ]),
            Section::make('الثوب والخياطة والمناسبة')->columns(2)->columnSpanFull()->schema([
                Select::make('fabric_id')->label('الثوب')->options(fn () => self::termOptions('fabric'))->placeholder('—'),
                Select::make('stitch_id')->label('نوع الخياطة')->options(fn () => self::termOptions('stitch'))->placeholder('—'),
                CheckboxList::make('occasions')->label('المناسبات')->relationship('occasions', 'name_ar', fn (Builder $query) => $query->where('type', 'occasion')->orderBy('sort'))->columns(5)->columnSpanFull(),
                Toggle::make('made_to_measure')->label('متوفر على المقاس (تفصال)'),
                Toggle::make('featured')->label('منتج مميز (الصفحة الرئيسية)'),
                Toggle::make('active')->label('منشور (ظاهر فالمتجر)')->default(true),
            ]),
            Section::make('الألوان والمقاسات والمخزون')->columnSpanFull()->schema([
                Section::make('توليد سريع')->compact()->columns(3)->schema([
                    CheckboxList::make('gen_colors')->label('الألوان')->options(fn () => self::termOptions('color'))->columns(5)->dehydrated(false)->columnSpanFull(),
                    TextInput::make('gen_sizes')->label('المقاسات (مفصولة بفاصلة)')->placeholder('S,M,L,XL')->dehydrated(false),
                    TextInput::make('gen_stock')->label('المخزون لكل سطر')->numeric()->default(5)->dehydrated(false),
                    Actions::make([
                        Action::make('generate')->label('توليد')->color('gray')->action(function (Get $get, Set $set) {
                            $rows = collect($get('variants') ?? []);
                            $have = $rows->map(fn ($r) => ($r['color_id'] ?? '').'|'.($r['size'] ?? ''))->all();
                            $sizes = array_values(array_filter(array_map('trim', explode(',', (string) $get('gen_sizes')))));
                            foreach (($get('gen_colors') ?: [null]) as $c) {
                                foreach ($sizes ?: [''] as $s) {
                                    if (! in_array($c.'|'.$s, $have)) {
                                        $rows->push(['color_id' => $c, 'size' => $s, 'stock' => (int) $get('gen_stock')]);
                                    }
                                }
                            }
                            $set('variants', $rows->values()->all());
                        }),
                    ])->verticalAlignment('end'),
                ]),
                Repeater::make('variants')->hiddenLabel()->relationship()->columns(3)->defaultItems(0)->addActionLabel('+ إضافة سطر')->schema([
                    Select::make('color_id')->label('اللون')->options(fn () => self::termOptions('color'))->placeholder('—'),
                    TextInput::make('size')->label('المقاس')->maxLength(12),
                    TextInput::make('stock')->label('المخزون')->numeric()->default(5)->minValue(0),
                ]),
            ]),
            Section::make('الصور')->description('الصورة الأولى هي الرئيسية. اسحب لإعادة الترتيب.')->columnSpanFull()->schema([
                Repeater::make('images')->hiddenLabel()->relationship()->orderColumn('sort')->reorderable()->grid(4)->defaultItems(0)->addActionLabel('+ صورة')->schema([
                    FileUpload::make('path')->hiddenLabel()->image()->disk('public')->directory('products')->visibility('public')
                        ->imageResizeMode('contain')->imageResizeTargetWidth('1400')->imageResizeTargetHeight('1400')->required(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['category', 'images', 'variants.color'])->withSum('variants as stock', 'stock'))
            ->columns([
                ImageColumn::make('image')->label('')->height(60)->width(45),
                TextColumn::make('name_ar')->label('المنتج')->searchable()->description(fn (Product $p) => $p->name_fr)->wrap(),
                TextColumn::make('segment')->label('الفئة')->badge()->formatStateUsing(fn ($state) => CategoryResource::SEGMENTS[$state] ?? $state),
                TextColumn::make('category.name_ar')->label('الصنف'),
                TextColumn::make('price')->label('الثمن')->suffix(' د.م')->sortable(),
                TextColumn::make('stock')->label('المخزون')->sortable()->color(fn ($state) => (int) $state === 0 ? 'danger' : null),
                ToggleColumn::make('featured')->label('مميز'),
                ToggleColumn::make('active')->label('منشور'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('segment')->label('الفئة')->options(CategoryResource::SEGMENTS),
                SelectFilter::make('category_id')->label('الصنف')->options(fn () => Category::orderBy('segment')->get()->mapWithKeys(fn ($c) => [$c->id => CategoryResource::SEGMENTS[$c->segment].' — '.$c->name_ar])->all()),
                TernaryFilter::make('active')->label('منشور'),
            ])
            ->recordActions([
                Action::make('view')->label('عرض')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->url(fn (Product $p) => route('product', $p->slug))->openUrlInNewTab(),
                EditAction::make(), DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
