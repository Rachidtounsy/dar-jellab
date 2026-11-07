# دار الجلابة | Dar Jellaba

Boutique en ligne de vêtements traditionnels marocains prêts à porter (djellabas, caftans, jabadors, daraa, selham, burnous, qachabia, takchita, babouches, tarbouche…) pour **hommes, femmes, garçons et filles**, par occasion (mariage, henné, fêtes, Ramadan).

**Stack :** Laravel 13 · Filament 4 (administration) · Blade · MySQL (SQLite en local) · arabe (RTL) + français.

## Installation (local)

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed        # listes de base + admin + produits de démo (local)
php artisan storage:link          # images produits (storage/app/public → public/storage)
php artisan serve
```

- Boutique : <http://127.0.0.1:8000>
- Administration : <http://127.0.0.1:8000/admin> (identifiants : `ADMIN_EMAIL` / `ADMIN_PASSWORD` du `.env`)
- Tests : `php artisan test`

## Production (cPanel / VPS)

1. PHP ≥ 8.3, MySQL, Composer. Le *document root* doit pointer sur `public/`.
2. `.env` : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `DB_*` (MySQL), `ADMIN_EMAIL`, `ADMIN_PASSWORD`.
3. `composer install --no-dev -o && php artisan migrate --seed --force && php artisan storage:link && php artisan optimize`.
4. Supprimez les produits de démo depuis l'admin avant la mise en ligne (ils ne sont créés qu'en local ou avec `SEED_DEMO=true`).

## Ce que le propriétaire gère depuis `/admin` (sans toucher au code)

| Section | Contenu |
|---|---|
| **المنتجات** | produits, prix / ancien prix, badge, mis en avant, publié, couture/tissu/occasions, **couleurs × tailles × stock** (génération rapide), photos multiples réordonnables |
| **الأصناف** | catégories par segment (رجال / نساء / أولاد / بنات), accessoires, ordre |
| **الألوان والخيارات** | couleurs (avec pastille), tissus, types de couture (sfifa, aqqad…), occasions |
| **الإعدادات** | nom, logo, bannière, **numéros WhatsApp**, téléphone, email, **adresses**, horaires, carte, **Facebook / Instagram / TikTok / YouTube / Telegram / Snapchat / Pinterest / X**, textes « à propos » et livraison, Meta Pixel |

## Structure

- `app/Models` : `Product`, `Category`, `Term` (couleur/tissu/couture/occasion), `Variant`, `ProductImage`, `Setting`
- `app/Http/Controllers/ShopController.php` : accueil, catalogue + filtres, fiche produit, sitemap, langue
- `app/Filament` : ressources d'administration + page *Réglages*
- `resources/views` : Blade (layout RTL/LTR, cartes, pages) · `lang/{ar,fr}/shop.php` : traductions
- `database/seeders` : `BaseSeeder` (listes), `DemoSeeder` (démo)

## Commande par WhatsApp

Chaque fiche produit génère un message WhatsApp prérempli (produit, couleur, taille, prix, lien, « sur mesure ») vers le numéro défini dans les réglages. Paiement à la livraison.

## À venir

Panier multi-articles, suivi des commandes, articles « culture marocaine », rapports.
