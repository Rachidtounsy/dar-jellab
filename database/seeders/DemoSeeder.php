<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Term;
use Illuminate\Database\Seeder;

/** Produits de démonstration (sans photos : silhouettes colorées). À supprimer avant la mise en ligne. */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::exists()) {
            return;
        }
        $T = Term::all()->mapWithKeys(fn ($t) => [$t->type.':'.$t->slug => $t->id]);
        $C = Category::all()->mapWithKeys(fn ($c) => [$c->segment.':'.$c->slug => $c->id]);
        $sizes = ['men' => ['M', 'L', 'XL', 'XXL'], 'women' => ['S', 'M', 'L', 'XL'], 'boys' => ['4', '6', '8', '10', '12'], 'girls' => ['4', '6', '8', '10', '12']];

        // segment, cat, اسم, nom, prix, ancien, couleurs, tissu, couture, occasions, badge, vedette, sur mesure
        $demo = [
            ['men', 'jellaba', 'جلابة رجالية بيضاء بالسفيفة', 'Djellaba homme blanche sfifa', 690, null, ['white', 'cream'], 'crepe', 'sfifa', ['friday', 'eid-fitr', 'wedding'], 'new', 1, 0],
            ['men', 'jellaba', 'جلابة رجالية مخططة أخضر وأسود', 'Djellaba homme rayée vert et noir', 790, 890, ['olive', 'black'], 'twill', 'aqqad', ['eid-adha', 'daily'], 'sale', 1, 0],
            ['men', 'jellaba', 'جلابة رجالية سوداء راقية', 'Djellaba homme noire élégante', 850, null, ['black', 'indigo'], 'wool', 'maamal', ['friday', 'wedding'], null, 1, 1],
            ['men', 'jabador', 'جابادور رجالي أزرق ملكي', 'Jabador homme bleu roi', 620, null, ['royal', 'indigo', 'sky'], 'cotton', 'plain', ['eid-fitr', 'daily'], null, 0, 0],
            ['men', 'daraa', 'دراعية رجالية ذهبية للعرس', 'Daraa homme dorée mariage', 1450, null, ['gold', 'cream'], 'silk', 'handmade', ['wedding', 'henna'], 'new', 1, 1],
            ['men', 'caftan', 'قفطان رجالي نبيذي', 'Caftan homme bordeaux', 1100, 1300, ['burgundy', 'emerald'], 'velvet', 'aqqad', ['wedding', 'engagement'], 'sale', 0, 1],
            ['men', 'selham', 'سلهام صوف رمادي', 'Selham laine gris', 1250, null, ['grey', 'brown', 'black'], 'wool', 'plain', ['daily'], null, 0, 0],
            ['men', 'burnous', 'برنوس أبيض', 'Burnous blanc', 1600, null, ['white', 'camel'], 'wool', 'plain', ['wedding', 'friday'], null, 0, 0],
            ['men', 'tarbouch', 'طربوش مغربي أحمر', 'Tarbouche marocain rouge', 90, null, ['red'], 'wool', 'plain', ['wedding', 'eid-fitr'], null, 0, 0],
            ['men', 'belgha', 'بلغة جلد أصفر', 'Babouches cuir jaune', 160, null, ['mustard', 'white', 'cream'], 'cotton', 'plain', ['daily', 'friday'], null, 0, 0],
            ['women', 'jellaba', 'جلابة نسائية بنية بالسفيفة الزرقاء', 'Djellaba femme caramel sfifa bleue', 890, null, ['caramel', 'cinnamon', 'pink'], 'crepe', 'sfifa', ['ramadan', 'eid-fitr'], 'new', 1, 0],
            ['women', 'jellaba', 'جلابة نسائية وردية بسفيفة رمادية', 'Djellaba femme rose sfifa grise', 820, 950, ['pink', 'lilac', 'cream'], 'crepe', 'sfifa', ['eid-fitr', 'daily'], 'sale', 1, 0],
            ['women', 'caftan', 'قفطان نسائي زمردي', 'Caftan femme émeraude', 1890, null, ['emerald', 'royal', 'burgundy'], 'satin', 'beads', ['wedding', 'henna'], 'new', 1, 1],
            ['women', 'caftan', 'قفطان نسائي فوشيا للحناء', 'Caftan femme fuchsia henné', 1650, null, ['fuchsia', 'orange', 'gold'], 'organza', 'handmade', ['henna', 'engagement'], null, 1, 1],
            ['women', 'takchita', 'تكشيطة عروس ذهبية', 'Takchita mariée dorée', 3900, null, ['gold', 'white', 'silver'], 'velvet', 'beads', ['wedding'], null, 1, 1],
            ['women', 'belgha', 'بلغة نسائية مطرزة', 'Babouches femme brodées', 210, null, ['gold', 'pink', 'turquoise'], 'velvet', 'handmade', ['wedding', 'daily'], null, 0, 0],
            ['boys', 'jellaba', 'جلابة ولد أبيض', 'Djellaba garçon blanche', 390, null, ['white', 'sky'], 'cotton', 'sfifa', ['eid-fitr', 'circumcision'], 'new', 1, 0],
            ['boys', 'set', 'طقم جابادور ولد للختان', 'Ensemble jabador garçon circoncision', 520, null, ['royal', 'white', 'gold'], 'satin', 'handmade', ['circumcision', 'eid-adha'], null, 0, 0],
            ['girls', 'caftan', 'قفطان بنت وردي', 'Caftan fille rose', 450, null, ['pink', 'lilac', 'mint'], 'satin', 'beads', ['eid-fitr', 'aqiqa'], 'new', 1, 0],
            ['girls', 'jellaba', 'جلابة بنت تركوازية', 'Djellaba fille turquoise', 420, 520, ['turquoise', 'pink'], 'crepe', 'sfifa', ['ramadan', 'daily'], 'sale', 0, 0],
        ];

        foreach ($demo as [$seg, $cat, $ar, $fr, $price, $old, $cols, $fab, $sti, $occ, $badge, $feat, $mtm]) {
            $p = Product::create([
                'segment' => $seg, 'category_id' => $C["$seg:$cat"], 'name_ar' => $ar, 'name_fr' => $fr,
                'desc_ar' => 'منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.', 'desc_fr' => "Produit de démonstration : remplacez ce texte depuis l'administration.",
                'price' => $price, 'old_price' => $old, 'fabric_id' => $T["fabric:$fab"], 'stitch_id' => $T["stitch:$sti"],
                'made_to_measure' => (bool) $mtm, 'badge' => $badge, 'featured' => (bool) $feat,
            ]);
            foreach ($cols as $c) {
                foreach ($sizes[$seg] as $s) {
                    $p->variants()->create(['color_id' => $T["color:$c"], 'size' => $s, 'stock' => random_int(0, 6)]);
                }
            }
            $p->occasions()->attach(collect($occ)->map(fn ($o) => $T["occasion:$o"])->all());
        }
    }
}
