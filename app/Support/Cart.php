<?php

namespace App\Support;

use App\Models\Product;

/** Panier stocké en session : clé "produit:variante:sur-mesure" → quantité. */
class Cart
{
    private const MAX_QTY = 10;

    public static function raw(): array
    {
        return session('cart', []);
    }

    public static function count(): int
    {
        return (int) array_sum(array_column(self::raw(), 'qty'));
    }

    public static function add(Product $p, ?int $colorId, string $size, int $qty, bool $mtm): ?string
    {
        $variants = $p->variants()->get();
        $variant = null;
        if ($variants->isNotEmpty()) {
            $variant = $variants->first(fn ($v) => (int) $v->color_id === (int) $colorId && $v->size === $size);
            if (! $variant) {
                return 'pick_options';
            }
        }
        $key = $p->id.':'.($variant->id ?? 0).':'.(int) ($mtm && $p->made_to_measure);
        $cart = self::raw();
        $have = $cart[$key]['qty'] ?? 0;
        $qty = max(1, min(self::MAX_QTY, $qty));
        if ($variant && $have + $qty > $variant->stock && ! ($mtm && $p->made_to_measure)) {
            return 'out_of_stock';
        }
        $cart[$key] = ['product_id' => $p->id, 'variant_id' => $variant?->id, 'mtm' => $mtm && $p->made_to_measure, 'qty' => min(self::MAX_QTY, $have + $qty)];
        session(['cart' => $cart]);

        return null;
    }

    public static function setQty(string $key, int $qty): void
    {
        $cart = self::raw();
        if (! isset($cart[$key])) {
            return;
        }
        if ($qty < 1) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = min(self::MAX_QTY, $qty);
        }
        session(['cart' => $cart]);
    }

    public static function clear(): void
    {
        session()->forget('cart');
    }

    /** Lignes résolues (les produits supprimés ou masqués sont ignorés). */
    public static function lines(): array
    {
        $raw = self::raw();
        if (! $raw) {
            return [];
        }
        $products = Product::active()->with(['images', 'variants.color', 'category'])->whereIn('id', array_column($raw, 'product_id'))->get()->keyBy('id');
        $lines = [];
        foreach ($raw as $key => $r) {
            $p = $products[$r['product_id']] ?? null;
            if (! $p) {
                continue;
            }
            $v = $r['variant_id'] ? $p->variants->firstWhere('id', $r['variant_id']) : null;
            if ($r['variant_id'] && ! $v) {
                continue;
            }
            $lines[] = ['key' => $key, 'product' => $p, 'variant' => $v, 'qty' => $r['qty'], 'mtm' => $r['mtm'], 'price' => $p->price,
                'color' => $v?->color?->name, 'size' => $v?->size, 'total' => $p->price * $r['qty'],
                'low' => $v && ! $r['mtm'] && $v->stock < $r['qty']];
        }

        return $lines;
    }

    public static function subtotal(array $lines): int
    {
        return (int) array_sum(array_column($lines, 'total'));
    }

    public static function shipping(int $subtotal): int
    {
        $fee = (int) setting('shipping_fee', '0');
        $free = (int) setting('free_shipping_min', '0');

        return ($free > 0 && $subtotal >= $free) ? 0 : $fee;
    }
}
