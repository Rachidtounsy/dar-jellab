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

    /** Nombre d'images ajoutées (les produits qui ont déjà une image sont ignorés). */
    public static function attach(): int
    {
        $added = 0;
        foreach (self::MAP as $nameFr => $file) {
            $source = database_path("seeders/demo-images/{$file}.svg");
            $product = Product::where('name_fr', $nameFr)->first();
            if (! $product || $product->images()->exists() || ! File::exists($source)) {
                continue;
            }
            $path = "products/demo/{$file}.svg";
            Storage::disk('public')->put($path, File::get($source));
            $product->images()->create(['path' => $path, 'sort' => 1]);
            $added++;
        }

        return $added;
    }
}
