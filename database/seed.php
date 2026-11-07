<?php
/** Données de base (listes) + démo optionnelle. */
function seed_base(PDO $pdo): void {
    $ins = fn($sql, $a) => $pdo->prepare($sql)->execute($a);
    // Couleurs de jellaba / caftan
    $colors = [
        ['white','أبيض','Blanc','#F5F2EA'],['cream','كريمي / بيج','Crème / Beige','#E8DCC2'],['camel','جملي','Camel','#C19A6B'],
        ['caramel','كراميل','Caramel','#B5703A'],['brown','بني / قهوة','Marron','#6B4226'],['cinnamon','قرفة','Cannelle','#B5532F'],
        ['orange','برتقالي','Orange','#E07B39'],['mustard','خردلي','Moutarde','#D4A72C'],['gold','ذهبي','Doré','#C8A24A'],
        ['olive','أخضر زيتي','Vert olive','#6B7A3A'],['emerald','أخضر زمردي','Vert émeraude','#0F7B5F'],['mint','أخضر نعناعي','Menthe','#9CC9A8'],
        ['turquoise','تركوازي','Turquoise','#1FA3A3'],['sky','أزرق سماوي','Bleu ciel','#7FB2E5'],['royal','أزرق ملكي','Bleu roi','#2347A8'],
        ['indigo','نيلي','Indigo','#1F3A5F'],['purple','بنفسجي','Violet','#6B3FA0'],['lilac','ليلكي','Lilas','#B79AD6'],
        ['pink','وردي','Rose','#E8A0B4'],['fuchsia','فوشيا','Fuchsia','#C2185B'],['red','أحمر','Rouge','#B3202A'],
        ['burgundy','نبيذي','Bordeaux','#6D1A2B'],['grey','رمادي','Gris','#9A9A9A'],['silver','فضي','Argent','#C0C0C0'],['black','أسود','Noir','#1B1B1B'],
    ];
    $i = 0;
    foreach ($colors as [$s, $ar, $fr, $hex]) $ins('INSERT INTO terms (type,slug,name_ar,name_fr,hex,sort) VALUES (?,?,?,?,?,?)', ['color', $s, $ar, $fr, $hex, $i++]);
    $lists = [
        'fabric' => [['crepe','كريب','Crêpe'],['satin','ساتان','Satin'],['silk','حرير','Soie'],['wool','صوف','Laine'],['cotton','قطن','Coton'],
                     ['twill','تويل','Twill'],['velvet','مخمل','Velours'],['linen','كتان','Lin'],['organza','أورجانزا','Organza'],['mlf','ملف','Mlf']],
        'stitch' => [['sfifa','سفيفة','Sfifa'],['aqqad','عقاد','Aqqad'],['maamal','معمّل','Ma\'mel'],['handmade','طرز يدوي','Broderie main'],
                     ['beads','ترصيع','Perlage'],['plain','سادة (بدون تطريز)','Uni (sans broderie)']],
        'occasion' => [['wedding','عرس','Mariage'],['henna','حنّاء','Henné'],['engagement','خطوبة','Fiançailles'],['eid-fitr','عيد الفطر','Aïd el-Fitr'],
                       ['eid-adha','عيد الأضحى','Aïd el-Adha'],['ramadan','رمضان','Ramadan'],['aqiqa','عقيقة','Aqiqa'],['circumcision','ختان','Circoncision'],
                       ['friday','الجمعة','Vendredi'],['daily','يومي','Quotidien']],
    ];
    foreach ($lists as $type => $items) { $i = 0; foreach ($items as [$s, $ar, $fr]) $ins('INSERT INTO terms (type,slug,name_ar,name_fr,sort) VALUES (?,?,?,?,?)', [$type, $s, $ar, $fr, $i++]); }
    // Catégories par segment
    $cats = [
        'men'   => [['jellaba','جلابة','Djellaba'],['jabador','جابادور','Jabador'],['daraa','دراعية','Daraa'],['caftan','قفطان','Caftan'],['selham','سلهام','Selham'],['burnous','برنوس','Burnous'],['qachaba','قشابة','Qachabia'],
                    ['tarbouch','طربوش','Tarbouche',1],['belgha','بلغة','Babouches',1]],
        'women' => [['jellaba','جلابة','Djellaba'],['caftan','قفطان','Caftan'],['takchita','تكشيطة','Takchita'],['belgha','بلغة','Babouches',1],['accessoires','إكسسوارات','Accessoires',1]],
        'boys'  => [['jellaba','جلابة','Djellaba'],['jabador','جابادور','Jabador'],['caftan','قفطان','Caftan'],['set','طقم','Ensemble'],['belgha','بلغة','Babouches',1]],
        'girls' => [['jellaba','جلابة','Djellaba'],['caftan','قفطان','Caftan'],['set','طقم','Ensemble'],['belgha','بلغة','Babouches',1]],
    ];
    foreach ($cats as $seg => $items) { $i = 0; foreach ($items as $c) $ins('INSERT INTO categories (segment,slug,name_ar,name_fr,accessory,sort) VALUES (?,?,?,?,?,?)', [$seg, $c[0], $c[1], $c[2], $c[3] ?? 0, $i++]); }
    // Réglages par défaut
    $defaults = [
        'site_name_ar' => 'دار الجلابة', 'site_name_fr' => 'Dar Jellaba',
        'tagline_ar' => 'لباس تقليدي مغربي للرجال والنساء والأطفال', 'tagline_fr' => 'Tenues traditionnelles marocaines pour hommes, femmes et enfants',
        'announce_ar' => 'التوصيل لجميع مدن المغرب · الأداء عند الاستلام · تبديل مجاني', 'announce_fr' => 'Livraison partout au Maroc · Paiement à la livraison · Échange gratuit',
        'hero_title_ar' => 'أناقة مغربية أصيلة', 'hero_title_fr' => 'L\'élégance marocaine authentique',
        'hero_text_ar' => 'جلابات وقفاطن جاهزة للرجال والنساء والأطفال، لكل مناسبة: العرس، الحنّاء، الأعياد ورمضان.',
        'hero_text_fr' => 'Djellabas et caftans prêts à porter pour hommes, femmes et enfants, pour chaque occasion : mariage, henné, fêtes et Ramadan.',
        'about_ar' => 'دار الجلابة متجر للباس المغربي التقليدي الجاهز. نختار بعناية الثوب والخياطة (السفيفة والعقاد) لنقدم لكم لباسا يليق بمناسباتكم.',
        'about_fr' => 'Dar Jellaba est une boutique de vêtements traditionnels marocains prêts à porter. Nous choisissons avec soin les tissus et les finitions (sfifa, aqqad).',
        'currency' => 'MAD', 'delivery_ar' => 'التوصيل لجميع المدن خلال 24 إلى 72 ساعة.', 'delivery_fr' => 'Livraison dans toutes les villes sous 24 à 72 h.',
    ];
    foreach ($defaults as $k => $v) $pdo->prepare('REPLACE INTO settings (k,v) VALUES (?,?)')->execute([$k, $v]);
}

function seed_demo(PDO $pdo): void {
    $T = []; foreach ($pdo->query('SELECT id,type,slug FROM terms') as $r) $T[$r['type'] . ':' . $r['slug']] = (int)$r['id'];
    $C = []; foreach ($pdo->query('SELECT id,segment,slug FROM categories') as $r) $C[$r['segment'] . ':' . $r['slug']] = (int)$r['id'];
    $demo = [
        // segment, cat, اسم, nom, prix, ancien, couleurs, tissu, couture, occasions, badge, vedette, sur mesure
        ['men','jellaba','جلابة رجالية بيضاء بالسفيفة','Djellaba homme blanche sfifa',690,null,['white','cream'],'crepe','sfifa',['friday','eid-fitr','wedding'],'new',1,0],
        ['men','jellaba','جلابة رجالية مخططة أخضر وأسود','Djellaba homme rayée vert et noir',790,890,['olive','black'],'twill','aqqad',['eid-adha','daily'],'sale',1,0],
        ['men','jellaba','جلابة رجالية سوداء راقية','Djellaba homme noire élégante',850,null,['black','indigo'],'wool','maamal',['friday','wedding'],null,1,1],
        ['men','jabador','جابادور رجالي أزرق ملكي','Jabador homme bleu roi',620,null,['royal','indigo','sky'],'cotton','plain',['eid-fitr','daily'],null,0,0],
        ['men','daraa','دراعية رجالية ذهبية للعرس','Daraa homme dorée mariage',1450,null,['gold','cream'],'silk','handmade',['wedding','henna'],'new',1,1],
        ['men','caftan','قفطان رجالي نبيذي','Caftan homme bordeaux',1100,1300,['burgundy','emerald'],'velvet','aqqad',['wedding','engagement'],'sale',0,1],
        ['men','selham','سلهام صوف رمادي','Selham laine gris',1250,null,['grey','brown','black'],'wool','plain',['daily'],null,0,0],
        ['men','burnous','برنوس أبيض','Burnous blanc',1600,null,['white','camel'],'wool','plain',['wedding','friday'],null,0,0],
        ['men','tarbouch','طربوش مغربي أحمر','Tarbouche marocain rouge',90,null,['red'],'wool','plain',['wedding','eid-fitr'],null,0,0],
        ['men','belgha','بلغة جلد أصفر','Babouches cuir jaune',160,null,['mustard','white','cream'],'cotton','plain',['daily','friday'],null,0,0],
        ['women','jellaba','جلابة نسائية بنية بالسفيفة الزرقاء','Djellaba femme caramel sfifa bleue',890,null,['caramel','cinnamon','pink'],'crepe','sfifa',['ramadan','eid-fitr'],'new',1,0],
        ['women','jellaba','جلابة نسائية وردية بسفيفة رمادية','Djellaba femme rose sfifa grise',820,950,['pink','lilac','cream'],'crepe','sfifa',['eid-fitr','daily'],'sale',1,0],
        ['women','caftan','قفطان نسائي زمردي','Caftan femme émeraude',1890,null,['emerald','royal','burgundy'],'satin','beads',['wedding','henna'],'new',1,1],
        ['women','caftan','قفطان نسائي فوشيا للحناء','Caftan femme fuchsia henné',1650,null,['fuchsia','orange','gold'],'organza','handmade',['henna','engagement'],null,1,1],
        ['women','takchita','تكشيطة عروس ذهبية','Takchita mariée dorée',3900,null,['gold','white','silver'],'velvet','beads',['wedding'],null,1,1],
        ['women','belgha','بلغة نسائية مطرزة','Babouches femme brodées',210,null,['gold','pink','turquoise'],'velvet','handmade',['wedding','daily'],null,0,0],
        ['boys','jellaba','جلابة ولد أبيض','Djellaba garçon blanche',390,null,['white','sky'],'cotton','sfifa',['eid-fitr','circumcision'],'new',1,0],
        ['boys','set','طقم جابادور ولد للختان','Ensemble jabador garçon circoncision',520,null,['royal','white','gold'],'satin','handmade',['circumcision','eid-adha'],null,0,0],
        ['girls','caftan','قفطان بنت وردي','Caftan fille rose',450,null,['pink','lilac','mint'],'satin','beads',['eid-fitr','aqiqa'],'new',1,0],
        ['girls','jellaba','جلابة بنت تركوازية','Djellaba fille turquoise',420,520,['turquoise','pink'],'crepe','sfifa',['ramadan','daily'],'sale',0,0],
    ];
    $sizesBy = ['men' => ['M','L','XL','XXL'], 'women' => ['S','M','L','XL'], 'boys' => ['4','6','8','10','12'], 'girls' => ['4','6','8','10','12']];
    $pIns = $pdo->prepare('INSERT INTO products (slug,segment,category_id,name_ar,name_fr,desc_ar,desc_fr,price,old_price,fabric_id,stitch_id,made_to_measure,badge,featured) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $vIns = $pdo->prepare('INSERT INTO variants (product_id,color_id,size,stock) VALUES (?,?,?,?)');
    $tIns = $pdo->prepare('INSERT INTO product_terms (product_id,term_id) VALUES (?,?)');
    foreach ($demo as $n => [$seg, $cat, $ar, $fr, $price, $old, $cols, $fab, $sti, $occ, $badge, $feat, $mtm]) {
        $pIns->execute([slugify($fr) . '-' . ($n + 1), $seg, $C["$seg:$cat"], $ar, $fr,
            'منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.', 'Produit de démonstration : remplacez ce texte depuis l\'administration.',
            $price, $old, $T["fabric:$fab"], $T["stitch:$sti"], $mtm, $badge, $feat]);
        $pid = (int)$pdo->lastInsertId();
        foreach ($cols as $c) foreach ($sizesBy[$seg] as $s) $vIns->execute([$pid, $T["color:$c"], $s, random_int(0, 6)]);
        foreach ($occ as $o) $tIns->execute([$pid, $T["occasion:$o"]]);
    }
}
