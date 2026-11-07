<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Term;
use Illuminate\Database\Seeder;

/** Listes de base : couleurs, tissus, coutures, occasions, catégories et réglages par défaut. */
class BaseSeeder extends Seeder
{
    public function run(): void
    {
        if (Term::exists()) {
            return;
        }

        $colors = [
            ['white', 'أبيض', 'Blanc', '#F5F2EA'], ['cream', 'كريمي / بيج', 'Crème / Beige', '#E8DCC2'], ['camel', 'جملي', 'Camel', '#C19A6B'],
            ['caramel', 'كراميل', 'Caramel', '#B5703A'], ['brown', 'بني / قهوة', 'Marron', '#6B4226'], ['cinnamon', 'قرفة', 'Cannelle', '#B5532F'],
            ['orange', 'برتقالي', 'Orange', '#E07B39'], ['mustard', 'خردلي', 'Moutarde', '#D4A72C'], ['gold', 'ذهبي', 'Doré', '#C8A24A'],
            ['olive', 'أخضر زيتي', 'Vert olive', '#6B7A3A'], ['emerald', 'أخضر زمردي', 'Vert émeraude', '#0F7B5F'], ['mint', 'أخضر نعناعي', 'Menthe', '#9CC9A8'],
            ['turquoise', 'تركوازي', 'Turquoise', '#1FA3A3'], ['sky', 'أزرق سماوي', 'Bleu ciel', '#7FB2E5'], ['royal', 'أزرق ملكي', 'Bleu roi', '#2347A8'],
            ['indigo', 'نيلي', 'Indigo', '#1F3A5F'], ['purple', 'بنفسجي', 'Violet', '#6B3FA0'], ['lilac', 'ليلكي', 'Lilas', '#B79AD6'],
            ['pink', 'وردي', 'Rose', '#E8A0B4'], ['fuchsia', 'فوشيا', 'Fuchsia', '#C2185B'], ['red', 'أحمر', 'Rouge', '#B3202A'],
            ['burgundy', 'نبيذي', 'Bordeaux', '#6D1A2B'], ['grey', 'رمادي', 'Gris', '#9A9A9A'], ['silver', 'فضي', 'Argent', '#C0C0C0'], ['black', 'أسود', 'Noir', '#1B1B1B'],
        ];
        foreach ($colors as $i => [$s, $ar, $fr, $hex]) {
            Term::create(['type' => 'color', 'slug' => $s, 'name_ar' => $ar, 'name_fr' => $fr, 'hex' => $hex, 'sort' => $i]);
        }

        $lists = [
            'fabric' => [['crepe', 'كريب', 'Crêpe'], ['satin', 'ساتان', 'Satin'], ['silk', 'حرير', 'Soie'], ['wool', 'صوف', 'Laine'], ['cotton', 'قطن', 'Coton'],
                ['twill', 'تويل', 'Twill'], ['velvet', 'مخمل', 'Velours'], ['linen', 'كتان', 'Lin'], ['organza', 'أورجانزا', 'Organza'], ['mlf', 'ملف', 'Mlf']],
            'stitch' => [['sfifa', 'سفيفة', 'Sfifa'], ['aqqad', 'عقاد', 'Aqqad'], ['maamal', 'معمّل', "Ma'mel"], ['handmade', 'طرز يدوي', 'Broderie main'],
                ['beads', 'ترصيع', 'Perlage'], ['plain', 'سادة (بدون تطريز)', 'Uni (sans broderie)']],
            'occasion' => [['wedding', 'عرس', 'Mariage'], ['henna', 'حنّاء', 'Henné'], ['engagement', 'خطوبة', 'Fiançailles'], ['eid-fitr', 'عيد الفطر', 'Aïd el-Fitr'],
                ['eid-adha', 'عيد الأضحى', 'Aïd el-Adha'], ['ramadan', 'رمضان', 'Ramadan'], ['aqiqa', 'عقيقة', 'Aqiqa'], ['circumcision', 'ختان', 'Circoncision'],
                ['friday', 'الجمعة', 'Vendredi'], ['daily', 'يومي', 'Quotidien']],
        ];
        foreach ($lists as $type => $items) {
            foreach ($items as $i => [$s, $ar, $fr]) {
                Term::create(['type' => $type, 'slug' => $s, 'name_ar' => $ar, 'name_fr' => $fr, 'sort' => $i]);
            }
        }

        $cats = [
            'men' => [['jellaba', 'جلابة', 'Djellaba'], ['jabador', 'جابادور', 'Jabador'], ['daraa', 'دراعية', 'Daraa'], ['caftan', 'قفطان', 'Caftan'], ['selham', 'سلهام', 'Selham'],
                ['burnous', 'برنوس', 'Burnous'], ['qachaba', 'قشابة', 'Qachabia'], ['tarbouch', 'طربوش', 'Tarbouche', 1], ['belgha', 'بلغة', 'Babouches', 1]],
            'women' => [['jellaba', 'جلابة', 'Djellaba'], ['caftan', 'قفطان', 'Caftan'], ['takchita', 'تكشيطة', 'Takchita'], ['belgha', 'بلغة', 'Babouches', 1], ['accessoires', 'إكسسوارات', 'Accessoires', 1]],
            'boys' => [['jellaba', 'جلابة', 'Djellaba'], ['jabador', 'جابادور', 'Jabador'], ['caftan', 'قفطان', 'Caftan'], ['set', 'طقم', 'Ensemble'], ['belgha', 'بلغة', 'Babouches', 1]],
            'girls' => [['jellaba', 'جلابة', 'Djellaba'], ['caftan', 'قفطان', 'Caftan'], ['set', 'طقم', 'Ensemble'], ['belgha', 'بلغة', 'Babouches', 1]],
        ];
        foreach ($cats as $seg => $items) {
            foreach ($items as $i => $c) {
                Category::create(['segment' => $seg, 'slug' => $c[0], 'name_ar' => $c[1], 'name_fr' => $c[2], 'accessory' => (bool) ($c[3] ?? 0), 'sort' => $i]);
            }
        }

        Setting::put([
            'site_name_ar' => 'دار الجلابة', 'site_name_fr' => 'Dar Jellaba',
            'tagline_ar' => 'لباس تقليدي مغربي للرجال والنساء والأطفال', 'tagline_fr' => 'Tenues traditionnelles marocaines pour hommes, femmes et enfants',
            'announce_ar' => 'التوصيل لجميع مدن المغرب · الأداء عند الاستلام · تبديل مجاني', 'announce_fr' => 'Livraison partout au Maroc · Paiement à la livraison · Échange gratuit',
            'hero_title_ar' => 'أناقة مغربية أصيلة', 'hero_title_fr' => "L'élégance marocaine authentique",
            'hero_text_ar' => 'جلابات وقفاطن جاهزة للرجال والنساء والأطفال، لكل مناسبة: العرس، الحنّاء، الأعياد ورمضان.',
            'hero_text_fr' => 'Djellabas et caftans prêts à porter pour hommes, femmes et enfants, pour chaque occasion : mariage, henné, fêtes et Ramadan.',
            'about_ar' => 'دار الجلابة متجر للباس المغربي التقليدي الجاهز. نختار بعناية الثوب والخياطة (السفيفة والعقاد) لنقدم لكم لباسا يليق بمناسباتكم.',
            'about_fr' => 'Dar Jellaba est une boutique de vêtements traditionnels marocains prêts à porter. Nous choisissons avec soin les tissus et les finitions (sfifa, aqqad).',
            'delivery_ar' => 'التوصيل لجميع المدن خلال 24 إلى 72 ساعة.', 'delivery_fr' => 'Livraison dans toutes les villes sous 24 à 72 h.',
        ]);
    }
}
