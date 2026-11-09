# دار الجلابة | Dar Jellaba

Boutique en ligne de vêtements traditionnels marocains prêts à porter (djellabas, caftans, jabadors, daraa, selham, burnous, qachabia, takchita, babouches, tarbouche…) pour **hommes, femmes, garçons et filles**, par occasion (mariage, henné, fêtes, Ramadan).

**Stack :** Laravel 13 · Filament 4 (administration) · Blade · MySQL (SQLite en local) · arabe (RTL) + français.

## Installation (local)

Prérequis : PHP ≥ 8.3 (extensions `pdo_sqlite`, `sqlite3`, `mbstring`, `intl`, `fileinfo`, `gd`, `zip`) et Composer.

```bash
composer install
composer setup        # .env, clé, base SQLite, migrations + données de base + démo
php artisan serve
```

(Équivalent manuel : `copy .env.example .env` · `php artisan key:generate` · créer `database/database.sqlite` · `php artisan migrate --seed`.)

- Boutique : <http://127.0.0.1:8000> · Administration : <http://127.0.0.1:8000/admin> (identifiants `ADMIN_EMAIL` / `ADMIN_PASSWORD` du `.env`)
- Les images envoyées sont stockées dans `public/storage` : **pas de `storage:link` nécessaire** (fonctionne sur Windows sans droits admin et sur cPanel).
- Envoi d'images volumineuses : dans `php.ini`, `upload_max_filesize = 20M` et `post_max_size = 25M`.
- Illustrations de démo sur les produits de démo : `php artisan demo:images`.
- Tests : `php artisan test`

## Production (cPanel / VPS)

1. PHP ≥ 8.3, MySQL, Composer. Le *document root* doit pointer sur `public/`.
2. `.env` : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `DB_*` (MySQL), `ADMIN_EMAIL`, `ADMIN_PASSWORD`.
3. `composer install --no-dev -o && php artisan migrate --seed --force && php artisan optimize` (les identifiants admin viennent de `config/shop.php` ← `.env`).
4. Supprimez les produits de démo depuis l'admin avant la mise en ligne (ils ne sont créés qu'en local ou avec `SEED_DEMO=true`).

## Ce que le propriétaire gère depuis `/admin` (sans toucher au code)

| Section | Contenu |
|---|---|
| **المنتجات** | produits, prix / ancien prix, badge, mis en avant, publié, couture/tissu/occasions, **couleurs × tailles × stock** (génération rapide), photos multiples réordonnables |
| **الأصناف** | catégories par segment (رجال / نساء / أولاد / بنات), accessoires, ordre |
| **الألوان والخيارات** | couleurs (avec pastille), tissus, types de couture (sfifa, aqqad…), occasions |
| **الطلبات** | طلبات الزبناء (جديد ← مؤكد ← شحن ← تسليم / ملغى / مرتجع)، واتساب الزبون، طباعة الوصل، تحديث جماعي؛ الإلغاء كيرجّع المخزون |
| **مقالات الثقافة** | مقالات (عرس، حنّاء، أعياد، رمضان، حرفة، الشمال) بمحرر نص وصورة غلاف، AR/FR |
| **الإعدادات** | nom, logo, bannière, **numéros WhatsApp**, téléphone, email, **adresses**, horaires, carte, **Facebook / Instagram / TikTok / YouTube / Telegram / Snapchat / Pinterest / X**, textes « à propos » et livraison, Meta Pixel |

## Structure

- `app/Models` : `Product`, `Category`, `Term` (couleur/tissu/couture/occasion), `Variant`, `ProductImage`, `Setting`
- `app/Http/Controllers/ShopController.php` : accueil, catalogue + filtres, fiche produit, sitemap, langue
- `app/Filament` : ressources d'administration + page *Réglages*
- `resources/views` : Blade (layout RTL/LTR, cartes, pages) · `lang/{ar,fr}/shop.php` : traductions
- `database/seeders` : `BaseSeeder` (listes), `DemoSeeder` (démo)

## Commande

- **Panier** (session) → **checkout sans compte** (nom, téléphone marocain validé, ville, adresse) → **paiement à la livraison**.
- Le stock des variantes est vérifié sous verrou puis décrémenté ; annuler/retourner une commande le remet.
- Page de confirmation avec bouton **WhatsApp** (récapitulatif prérempli vers le numéro du magasin) et événement Meta Pixel `Purchase`.
- Alternative rapide : bouton « Commander sur WhatsApp » sur chaque fiche produit.
- Frais de livraison et seuil de livraison gratuite : *الإعدادات ← التوصيل والطلبات*.

## Culture & aide

- `/culture` : articles (7 articles de départ à relire/adapter), liés aux occasions (produits suggérés).
- `/size-guide` : guide des tailles (valeurs indicatives, à ajuster selon vos coupes).
- SEO : balises Open Graph, JSON-LD `Product`, `sitemap.xml`.

## Tests

`php artisan test` : pages publiques, filtres, panier/commande/stock, annulation, réglages, culture, accès admin.
