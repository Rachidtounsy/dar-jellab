<?php

namespace App\Filament\Resources\Terms;

use App\Filament\Resources\Terms\Pages\CreateTerm;
use App\Filament\Resources\Terms\Pages\EditTerm;
use App\Filament\Resources\Terms\Pages\ListTerms;
use App\Models\Term;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TermResource extends Resource
{
    protected static ?string $model = Term::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static ?string $modelLabel = 'خيار';

    protected static ?string $pluralModelLabel = 'الألوان والخيارات';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')->label('النوع')->options(Term::TYPES)->required()->live(),
            TextInput::make('name_ar')->label('الاسم (عربية)')->required()->maxLength(120),
            TextInput::make('name_fr')->label('الاسم (فرنسية)')->maxLength(120),
            ColorPicker::make('hex')->label('اللون')->visible(fn (Get $get) => $get('type') === 'color')->required(fn (Get $get) => $get('type') === 'color'),
            TextInput::make('sort')->label('الترتيب')->numeric()->default(0),
            Toggle::make('active')->label('مفعل')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->label('النوع')->badge()->formatStateUsing(fn ($state) => Term::TYPES[$state] ?? $state)->sortable(),
                ColorColumn::make('hex')->label('اللون'),
                TextColumn::make('name_ar')->label('عربية')->searchable(),
                TextColumn::make('name_fr')->label('Français')->searchable(),
                ToggleColumn::make('active')->label('مفعل'),
                TextColumn::make('sort')->label('الترتيب')->sortable(),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->filters([SelectFilter::make('type')->label('النوع')->options(Term::TYPES)->default('color')])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTerms::route('/'),
            'create' => CreateTerm::route('/create'),
            'edit' => EditTerm::route('/{record}/edit'),
        ];
    }
}
