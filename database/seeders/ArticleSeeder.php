<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

/** Articles « culture marocaine » de départ (à relire / adapter depuis l'administration). */
class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        if (Article::exists()) {
            return;
        }

        $rows = [
            ['craft', 'الجلابة المغربية: لباس يتوارثه المغاربة', 'La djellaba marocaine : un vêtement qui se transmet',
                'من الجبة الصوفية البسيطة إلى الجلابة الراقية بالسفيفة، حكاية لباس رافق المغاربة في اليومي والمناسبات.',
                'De la tenue en laine simple à la djellaba raffinée ornée de sfifa, histoire d\'un vêtement du quotidien et des grandes occasions.',
                '<p>الجلابة لباس طويل فضفاض بقب (غطاء رأس مدبب)، وهي من أكثر الألبسة التقليدية حضورا فالمغرب، رجالا ونساء. كتلبس فالجمعة والأعياد والمناسبات العائلية، وكتخدم بأثواب مختلفة حسب الفصل: الصوف للبرد، والقطن والكريب للأيام المعتدلة.</p><h2>اختلاف الأذواق من مدينة لمدينة</h2><p>كل منطقة عندها لمستها: من الجلابة البيضاء البسيطة، للمخططة بالألوان، لجلابة النساء المطرزة بالسفيفة والعقاد. وتبقى القصة الجيدة والثوب المريح هما سر جلابة تعيش سنين.</p><h2>كيفاش تختار جلابتك</h2><ul><li>حدد المناسبة: يومي، جمعة، عيد أو عرس.</li><li>اختار الثوب حسب الفصل.</li><li>شوف نوع الخياطة والتزيين: سفيفة، عقاد أو طرز يدوي.</li><li>تأكد من المقاس عبر <a href="/size-guide">جدول المقاسات</a>.</li></ul>',
                '<p>La djellaba est un vêtement long et ample à capuche pointue, porté par les hommes comme par les femmes pour la prière du vendredi, les fêtes et les réunions de famille. Laine pour l\'hiver, coton ou crêpe pour la mi-saison.</p><h2>Comment choisir</h2><ul><li>Définissez l\'occasion.</li><li>Choisissez le tissu selon la saison.</li><li>Regardez la finition : sfifa, aqqad ou broderie main.</li><li>Vérifiez votre taille avec le guide des tailles.</li></ul>'],
            ['wedding', 'القفطان المغربي في الأعراس', 'Le caftan marocain dans les mariages',
                'القفطان والتكشيطة حاضرين فكل عرس مغربي: ألوان، أثواب فاخرة وتفاصيل يدوية.',
                'Caftan et takchita sont de tous les mariages marocains : couleurs, tissus nobles et détails faits main.',
                '<p>فالعرس المغربي كتبدل العروسة والضيوف أكثر من لبسة: قفطان، تكشيطة (قطعتين أو أكثر)، وأحيانا الجلابة. الأثواب الفاخرة بحال المخمل والساتان والأورجانزا مع الترصيع والطرز اليدوي كتعطي للمناسبة بريقها.</p><h2>لباس العريس</h2><p>العريس كيختار غالبا جلابة أنيقة بيضاء أو بلون هادئ، مع البلغة والطربوش، أو دراعية فاخرة لليلة الحناء.</p><h2>نصائح</h2><ul><li>اختار لون يناسب تفاصيل العرس والإضاءة.</li><li>جرب القفطان قبل المناسبة بوقت كافي.</li><li>إلا بغيتي قياس خاص، سول على التفصال.</li></ul>',
                '<p>Lors d\'un mariage marocain, la mariée et les invités portent souvent plusieurs tenues : caftan, takchita (deux pièces ou plus), parfois la djellaba. Velours, satin et organza, rehaussés de perlage et de broderie main, donnent tout leur éclat à la fête.</p><h2>La tenue du marié</h2><p>Djellaba élégante blanche ou sobre, babouches et tarbouche, ou daraa raffinée pour la soirée du henné.</p>'],
            ['craft', 'السفيفة والعقاد: فن التزيين بالخيوط', 'Sfifa et aqqad : l\'art de la passementerie',
                'التزيين اللي كيعطي للجلابة والقفطان هويتهم: خيوط مجدولة وعقد صغيرة بيد الحرفي.',
                'La passementerie qui donne leur identité à la djellaba et au caftan : cordons tressés et petits boutons noués à la main.',
                '<p><strong>السفيفة</strong> شريط مزخرف من الخيوط المجدولة كيتحط على حواف الجلابة والقفطان: على الصدر والأكمام والقب. و<strong>العقاد</strong> هي العقد الصغيرة المصنوعة بالخيط واللي كتعوض الأزرار وكتزين الفتحة الأمامية.</p><h2>عمل يدوي دقيق</h2><p>الحرفي كيصنع الخيوط ويجدلها ويخيطها بعناية، وكل قطعة كتاخد وقت. علاش القطع المزينة بالسفيفة والعقاد كتكون أغلى وأخلد.</p><h2>كيفاش تعتني بها</h2><ul><li>الغسيل بالماء الفاتر أو التنظيف الجاف.</li><li>تفادى الفرك القوي على التطريز.</li><li>علق الجلابة بدل ما تطويها بزاف.</li></ul>',
                '<p>La <strong>sfifa</strong> est un galon décoratif de fils tressés posé sur les bords du vêtement ; l\'<strong>aqqad</strong> désigne les petits nœuds qui remplacent les boutons. Un travail minutieux d\'artisan, qui fait toute la valeur de la pièce.</p>'],
            ['henna', 'ليلة الحناء: عادات وألوان', 'La soirée du henné : traditions et couleurs',
                'ليلة مليئة بالفرح والأغاني والألوان الزاهية، ولباسها كيعكس الفرح.',
                'Une soirée de joie, de chants et de couleurs vives, où le vêtement reflète la fête.',
                '<p>ليلة الحناء من أجمل لحظات العرس المغربي: كتجتمع العائلة والصديقات، وكتتزين العروسة بالحناء. اللباس فهاد الليلة كيكون عادة بألوان زاهية: الفوشيا، الأخضر، الأزرق الملكي والذهبي.</p><h2>لبسة الحناء</h2><p>القفطان الخفيف بالأورجانزا أو الساتان خيار مفضل، وكيتكمل بالحلي التقليدية. بالنسبة للرجال، دراعية أو جلابة بلون دافئ.</p>',
                '<p>La soirée du henné réunit la famille et les amies autour de la mariée. On y porte des couleurs vives : fuchsia, vert, bleu roi et doré ; caftan léger en organza ou satin pour elle, daraa ou djellaba chaude pour lui.</p>'],
            ['eid', 'لباس العيد: فرحة الكسوة الجديدة', 'Tenue de l\'Aïd : le plaisir des habits neufs',
                'من العادات الجميلة أن نلبسو الجديد فالعيد، وخصوصا الأطفال.',
                'Étrenner de nouveaux habits à l\'Aïd est une belle coutume, surtout pour les enfants.',
                '<p>فالعيد كيحرص المغاربة على لبس الجديد: جلابة للأب والولد، قفطان أو جلابة للأم والبنت. الصباح كتبدا بالصلاة وكتتكمل بالزيارات العائلية، واللباس التقليدي كيزيد الفرحة.</p><h2>أفكار للعائلة</h2><ul><li>جلابات بألوان منسجمة للأسرة كلها.</li><li>طقم جابادور للولد وقفطان للبنت.</li><li>بلغة جديدة لكل واحد.</li></ul>',
                '<p>À l\'Aïd, on étrenne des habits neufs : djellaba pour le père et le fils, caftan ou djellaba pour la mère et la fille. Une journée qui commence par la prière et se poursuit en visites familiales.</p>'],
            ['north', 'أناقة الشمال: تطوان وشفشاون', 'L\'élégance du Nord : Tétouan et Chefchaouen',
                'الشمال المغربي ورث من الأندلس ذوقا رفيعا فاللباس والخياطة.',
                'Le Nord du Maroc a hérité de l\'Andalousie un goût raffiné pour le vêtement et la couture.',
                '<p>تطوان، المعروفة بـ"الحمامة البيضاء"، وشفشاون بأزقتها الزرقاء، من المدن اللي حافظات على تراث غني فالخياطة والتطريز. للمنطقة طابعها فالألوان والتفاصيل، وكتعتز بحرفييها.</p><p>فدار الجلابة كنحاولو نكرمو هاد الذوق الشمالي بتشكيلة فيها الجلابات والقفاطن والبلغة لكل المناسبات.</p>',
                '<p>Tétouan, la « Colombe blanche », et Chefchaouen, ses ruelles bleues, ont préservé un riche patrimoine de couture et de broderie. Chez Dar Jellaba, nous rendons hommage à ce goût du Nord.</p>'],
            ['ramadan', 'رمضان: الجلابة والطقوس', 'Ramadan : la djellaba et les rituels',
                'جلابة مريحة وأنيقة لأيام وليالي رمضان.',
                'Une djellaba confortable et élégante pour les jours et les nuits de Ramadan.',
                '<p>فرمضان كتولي الجلابة اللباس المفضل للتراويح والزيارات والسهرات العائلية. الأثواب الخفيفة بحال الكريب والقطن كتعطي راحة، والألوان الهادئة كتناسب الأجواء الروحانية.</p>',
                '<p>Pendant Ramadan, la djellaba devient la tenue idéale pour les tarawih, les visites et les soirées en famille. Tissus légers et couleurs douces conviennent à l\'ambiance spirituelle du mois.</p>'],
        ];

        foreach ($rows as $i => [$topic, $tAr, $tFr, $eAr, $eFr, $bAr, $bFr]) {
            Article::create(['topic' => $topic, 'title_ar' => $tAr, 'title_fr' => $tFr, 'excerpt_ar' => $eAr, 'excerpt_fr' => $eFr, 'body_ar' => $bAr, 'body_fr' => $bFr, 'published_at' => now()->subDays($i)]);
        }
    }
}
