<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Models\Article;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $modelLabel = 'مقال';

    protected static ?string $pluralModelLabel = 'مقالات الثقافة';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'title_ar';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                Select::make('topic')->label('الموضوع')->options(Article::TOPICS)->required()->default('other'),
                DateTimePicker::make('published_at')->label('تاريخ النشر')->default(now()),
                TextInput::make('title_ar')->label('العنوان (عربية)')->required()->maxLength(190),
                TextInput::make('title_fr')->label('العنوان (فرنسية)')->maxLength(190),
                Textarea::make('excerpt_ar')->label('مقدمة قصيرة (عربية)')->rows(2),
                Textarea::make('excerpt_fr')->label('مقدمة قصيرة (فرنسية)')->rows(2),
                FileUpload::make('cover')->label('صورة الغلاف')->image()->disk('public')->directory('articles')->visibility('public')
                    ->imageResizeMode('cover')->imageResizeTargetWidth('1400')->imageResizeTargetHeight('900')->columnSpanFull(),
                Toggle::make('published')->label('منشور')->default(true),
            ]),
            Section::make('المحتوى')->columnSpanFull()->schema([
                RichEditor::make('body_ar')->label('النص (عربية)'),
                RichEditor::make('body_fr')->label('النص (فرنسية)'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')->label('')->disk('public')->height(48),
                TextColumn::make('title_ar')->label('العنوان')->searchable()->wrap(),
                TextColumn::make('topic')->label('الموضوع')->badge()->formatStateUsing(fn ($state) => Article::TOPICS[$state] ?? $state),
                TextColumn::make('published_at')->label('النشر')->dateTime('Y-m-d')->sortable(),
                ToggleColumn::make('published')->label('منشور'),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([SelectFilter::make('topic')->label('الموضوع')->options(Article::TOPICS)])
            ->recordActions([
                Action::make('view')->label('عرض')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->url(fn (Article $a) => route('culture.show', $a->slug))->openUrlInNewTab(),
                EditAction::make(), DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
