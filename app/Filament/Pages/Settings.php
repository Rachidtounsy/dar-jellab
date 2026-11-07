<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/** Tous les réglages du site (WhatsApp, adresses, réseaux sociaux...) : modifiables sans toucher au code. */
class Settings extends Page
{
    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'الإعدادات';

    protected static ?string $title = 'الإعدادات';

    protected static ?int $navigationSort = 9;

    public ?array $data = [];

    private const URLS = ['facebook', 'instagram', 'tiktok', 'youtube', 'telegram', 'snapchat', 'pinterest', 'x', 'map_url'];

    public function mount(): void
    {
        $this->form->fill(Setting::all_());
    }

    public function form(Schema $schema): Schema
    {
        $txt = fn (string $k, string $l, ?string $ph = null) => TextInput::make($k)->label($l)->placeholder($ph);
        $url = fn (string $k, string $l) => TextInput::make($k)->label($l)->placeholder('https://')->extraInputAttributes(['dir' => 'ltr']);

        return $schema->statePath('data')->components([
            Tabs::make('settings')->columnSpanFull()->tabs([
                Tab::make('الهوية')->schema([
                    Section::make()->columns(2)->schema([
                        $txt('site_name_ar', 'اسم المتجر (عربية)'), $txt('site_name_fr', 'اسم المتجر (فرنسية)'),
                        $txt('tagline_ar', 'الشعار النصي (عربية)'), $txt('tagline_fr', 'الشعار النصي (فرنسية)'),
                        $txt('announce_ar', 'شريط الإعلان (عربية)'), $txt('announce_fr', 'شريط الإعلان (فرنسية)'),
                        FileUpload::make('logo')->label('اللوغو (اختياري، يعوض اللوغو الافتراضي)')->image()->disk('public')->directory('site')->visibility('public'),
                    ]),
                ]),
                Tab::make('الصفحة الرئيسية')->schema([
                    Section::make()->columns(2)->schema([
                        $txt('hero_title_ar', 'عنوان البانر (عربية)'), $txt('hero_title_fr', 'عنوان البانر (فرنسية)'),
                        Textarea::make('hero_text_ar')->label('نص البانر (عربية)')->rows(3), Textarea::make('hero_text_fr')->label('نص البانر (فرنسية)')->rows(3),
                        FileUpload::make('hero_image')->label('صورة البانر (اختياري)')->image()->disk('public')->directory('site')->visibility('public')->columnSpanFull(),
                    ]),
                ]),
                Tab::make('التواصل')->schema([
                    Section::make()->columns(2)->schema([
                        $txt('whatsapp1', 'واتساب 1 (بصيغة دولية)', '212600000000')->extraInputAttributes(['dir' => 'ltr']),
                        $txt('whatsapp2', 'واتساب 2 (اختياري)', '212600000000')->extraInputAttributes(['dir' => 'ltr']),
                        $txt('phone', 'الهاتف')->extraInputAttributes(['dir' => 'ltr']),
                        TextInput::make('email')->label('البريد الإلكتروني')->email()->extraInputAttributes(['dir' => 'ltr']),
                        Textarea::make('address_ar')->label('العنوان (عربية)')->rows(2), Textarea::make('address_fr')->label('العنوان (فرنسية)')->rows(2),
                        $url('map_url', 'رابط الخريطة (Google Maps)'),
                        $txt('hours_ar', 'أوقات العمل (عربية)'), $txt('hours_fr', 'أوقات العمل (فرنسية)'),
                    ]),
                ]),
                Tab::make('مواقع التواصل')->schema([
                    Section::make()->columns(2)->schema([
                        $url('facebook', 'رابط صفحة فيسبوك'), $url('instagram', 'رابط إنستغرام'), $url('tiktok', 'رابط تيك توك'), $url('youtube', 'رابط يوتيوب'),
                        $url('telegram', 'رابط تيليغرام'), $url('snapchat', 'رابط سناب شات'), $url('pinterest', 'رابط بنتريست'), $url('x', 'رابط X (تويتر)'),
                    ]),
                ]),
                Tab::make('المحتوى')->schema([
                    Section::make()->columns(2)->schema([
                        Textarea::make('about_ar')->label('من نحن (عربية)')->rows(4), Textarea::make('about_fr')->label('من نحن (فرنسية)')->rows(4),
                        Textarea::make('delivery_ar')->label('التوصيل (عربية)')->rows(3), Textarea::make('delivery_fr')->label('التوصيل (فرنسية)')->rows(3),
                    ]),
                ]),
                Tab::make('التتبع')->schema([
                    Section::make()->schema([$txt('meta_pixel', 'Meta Pixel ID (اختياري)')->extraInputAttributes(['dir' => 'ltr'])]),
                ]),
            ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        foreach (self::URLS as $k) {
            $v = trim((string) ($data[$k] ?? ''));
            $data[$k] = ($v !== '' && ! preg_match('#^https?://#i', $v)) ? 'https://'.$v : $v;
        }
        // FileUpload peut renvoyer un tableau : on garde le chemin
        foreach (['logo', 'hero_image'] as $k) {
            $data[$k] = is_array($data[$k] ?? null) ? (array_values($data[$k])[0] ?? '') : ($data[$k] ?? '');
        }
        Setting::put(array_map(fn ($v) => is_string($v) ? trim($v) : $v, $data));

        Notification::make()->success()->title('تم حفظ الإعدادات')->send();
    }
}
