# دار الجلابة | Dar Jellaba

Boutique en ligne de vêtements traditionnels marocains prêts à porter (djellabas, caftans, jabadors, daraa, selham, burnous, qachabia, takchita, babouches, tarbouche…) pour **hommes, femmes, garçons et filles**, par occasion (mariage, henné, fêtes, Ramadan).

**Stack :** Laravel 13 · Filament 4 (administration) · Blade · **MySQL** · arabe (RTL) + français.

## Installation (local) — MySQL

Prérequis : PHP ≥ 8.3 (extensions `pdo_mysql`, `mbstring`, `intl`, `fileinfo`, `gd`, `zip`), Composer et **MySQL 8 / MariaDB** (XAMPP, Laragon, MySQL Installer…).

**Option A — import du dump prêt à l'emploi (recommandé pour tester)**

```bash
mysql -u root < database/mysql/dar_jellaba.sql     # crée la base `darjellaba` + tables + données de démo
composer install
copy .env.example .env                             # (cp sous Linux/Mac) puis : php artisan key:generate
php artisan demo:images                            # restaure les illustrations de démo (public/storage)
php artisan serve
```

**Option B — migrations (base vide)** : créez la base puis `composer setup`

```sql
CREATE DATABASE darjellaba CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Réglages dans `.env` : `DB_HOST`, `DB_PORT`, `DB_DATABASE=darjellaba`, `DB_USERNAME=root`, `DB_PASSWORD=` (adaptez au mot de passe de votre MySQL).

- Boutique : <http://127.0.0.1:8000> · Administration : <http://127.0.0.1:8000/admin>
  (compte du dump / seeder : `admin@darjellaba.ma` · `ChangeMe-2026!` — à changer).
- Images : stockées dans `public/storage`, **pas de `storage:link`** (Windows sans droits admin, cPanel).
- Envoi d'images : dans `php.ini`, `upload_max_filesize = 20M`, `post_max_size = 25M` et un `upload_tmp_dir` / `sys_temp_dir` **inscriptible** (ex. `C:\php-tmp`).
- Tests : créez aussi la base `darjellaba_testing`, puis `php artisan test` (MySQL, comme en production).

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
