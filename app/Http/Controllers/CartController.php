<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function show()
    {
        $lines = Cart::lines();
        $sub = Cart::subtotal($lines);

        return view('shop.cart', ['lines' => $lines, 'subtotal' => $sub, 'shipping' => Cart::shipping($sub)]);
    }

    public function add(Request $r)
    {
        $data = $r->validate(['product_id' => 'required|integer', 'color_id' => 'nullable|integer', 'size' => 'nullable|string|max:12', 'qty' => 'nullable|integer|min:1|max:10', 'mtm' => 'nullable']);
        $p = Product::active()->findOrFail($data['product_id']);
        $err = Cart::add($p, $data['color_id'] ?? null, (string) ($data['size'] ?? ''), (int) ($data['qty'] ?? 1), (bool) ($data['mtm'] ?? false));
        if ($err) {
            return back()->with('error', t($err));
        }

        return redirect()->route('cart')->with('ok', t('added_to_cart'));
    }

    public function update(Request $r)
    {
        foreach ((array) $r->input('qty', []) as $key => $q) {
            Cart::setQty((string) $key, (int) $q);
        }

        return redirect()->route('cart');
    }

    public function remove(Request $r)
    {
        Cart::setQty((string) $r->input('key'), 0);

        return redirect()->route('cart');
    }

    public function checkout()
    {
        $lines = Cart::lines();
        if (! $lines) {
            return redirect()->route('cart');
        }
        $sub = Cart::subtotal($lines);

        return view('shop.checkout', ['lines' => $lines, 'subtotal' => $sub, 'shipping' => Cart::shipping($sub)]);
    }

    public function place(Request $r)
    {
        if ($r->filled('website')) {                       // honeypot anti-spam
            return redirect()->route('home');
        }
        $data = $r->validate([
            'name' => 'required|string|min:3|max:120',
            'phone' => ['required', 'regex:/^(?:\+?212|0)[5-7]\d{8}$/'],
            'city' => 'required|string|max:80',
            'address' => 'nullable|string|max:400',
            'notes' => 'nullable|string|max:600',
        ], ['phone.regex' => t('phone_invalid')]);
        $data['phone'] = preg_replace('/[\s.\-]/', '', $data['phone']);

        $lines = Cart::lines();
        if (! $lines) {
            return redirect()->route('cart');
        }

        $order = DB::transaction(function () use ($lines, $data) {
            foreach ($lines as $l) {                         // re-vérifier le stock sous verrou
                if ($l['variant'] && ! $l['mtm']) {
                    $v = Variant::whereKey($l['variant']->id)->lockForUpdate()->first();
                    if (! $v || $v->stock < $l['qty']) {
                        return null;
                    }
                }
            }
            $sub = Cart::subtotal($lines);
            $ship = Cart::shipping($sub);
            $order = Order::create($data + ['subtotal' => $sub, 'shipping' => $ship, 'total' => $sub + $ship, 'locale' => app()->getLocale()]);
            foreach ($lines as $l) {
                $order->items()->create([
                    'product_id' => $l['product']->id, 'variant_id' => $l['variant']?->id, 'name' => $l['product']->localized('name') ?: $l['product']->name_ar,
                    'color' => $l['color'], 'size' => $l['size'], 'made_to_measure' => $l['mtm'], 'price' => $l['price'], 'qty' => $l['qty'],
                ]);
                if ($l['variant'] && ! $l['mtm']) {
                    Variant::whereKey($l['variant']->id)->decrement('stock', $l['qty']);
                }
            }

            return $order;
        });

        if (! $order) {
            return redirect()->route('cart')->with('error', t('out_of_stock'));
        }
        Cart::clear();

        return redirect()->route('order', $order->ref);
    }

    public function order(string $ref)
    {
        $order = Order::with('items')->where('ref', $ref)->firstOrFail();

        return view('shop.order', compact('order'));
    }
}
