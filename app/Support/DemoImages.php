<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/** Illustrations vectorielles de démonstration rattachées aux produits de démo (sans photo). */
class DemoImages
{
    /** @var array<string, string> nom français du produit => fichier SVG */
    public const MAP = [
        'Djellaba homme blanche sfifa' => 'men-white-classic',
        'Djellaba homme rayée vert et noir' => 'men-striped-olive',
        'Djellaba homme noire élégante' => 'men-black-tone',
        'Djellaba femme caramel sfifa bleue' => 'women-caramel-blue',
        'Djellaba femme rose sfifa grise' => 'women-pink-sfifa',
    ];

    /**
     * Rattache les illustrations aux produits de démo qui n'ont pas d'image et
     * restaure les fichiers manquants (ex. après import d'un dump SQL).
     *
     * @return int nombre de lignes ajoutées + fichiers restaurés
     */
    public static function attach(): int
    {
        $changed = 0;
        $disk = Storage::disk('public');
        foreach (self::MAP as $nameFr => $file) {
            $source = database_path("seeders/demo-images/{$file}.svg");
            $product = Product::where('name_fr', $nameFr)->first();
            if (! $product || ! File::exists($source)) {
                continue;
            }
            $path = "products/demo/{$file}.svg";
            if (! $product->images()->exists()) {
                $product->images()->create(['path' => $path, 'sort' => 1]);
                $changed++;
            }
            if ($product->images()->where('path', $path)->exists() && ! $disk->exists($path)) {
                $disk->put($path, File::get($source));
                $changed++;
            }
        }

        return $changed;
    }
}
