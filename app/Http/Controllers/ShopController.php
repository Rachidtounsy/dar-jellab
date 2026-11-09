<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use App\Models\Term;
use App\Support\Placeholder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    private const WITH_CARD = ['category', 'images', 'variants.color'];

    public function home()
    {
        $base = fn () => Product::active()->with(self::WITH_CARD);
        $feat = $base()->where('featured', true)->sortBy('popular')->limit(8)->get();

        return view('shop.home', [
            'feat' => $feat->isNotEmpty() ? $feat : $base()->sortBy('popular')->limit(8)->get(),
            'new' => $base()->sortBy('new')->limit(8)->get(),
            'sale' => $base()->whereColumn('old_price', '>', 'price')->limit(4)->get(),
            'articles' => Article::published()->limit(3)->get(),
            'counts' => Product::active()->select('segment', DB::raw('count(*) n'))->groupBy('segment')->pluck('n', 'segment'),
        ]);
    }

    /** Catalogue : /shop, /shop/{segment}, /shop/{segment}/{cat}, /accessories, /sale, /occasion/{slug}, /search */
    public function index(Request $r, ?string $segment = null, ?string $cat = null, array $force = [], ?string $path = null)
    {
        $f = array_filter($r->only(['q', 'sort', 'color', 'fabric', 'stitch', 'occasion', 'min', 'max']), fn ($v) => $v !== null && $v !== '');
        $f = $force + $f;
        if ($segment) {
            abort_unless(in_array($segment, Category::SEGMENTS, true), 404);
            $f['segment'] = $segment;
        }
        if ($cat) {
            $f['cat'] = $cat;
        }

        $title = t('all_products');
        if ($segment) {
            $title = segment_label($segment);
        }
        if ($segment && $cat) {
            $c = Category::where('segment', $segment)->where('slug', $cat)->firstOrFail();
            $title = $c->name.' — '.segment_label($segment);
        }
        if (! empty($f['accessory'])) {
            $title = t('accessories');
        }
        if (! empty($f['sale'])) {
            $title = t('sale');
        }
        if (! empty($f['occasion']) && ($o = Term::where('type', 'occasion')->where('slug', $f['occasion'])->first())) {
            $title = $o->name;
        }
        if (! empty($f['q'])) {
            $title = t('search').': '.$f['q'];
        }

        $products = Product::active()->with(self::WITH_CARD)->filter($f)->sortBy($f['sort'] ?? 'new')->paginate(24)->withQueryString();

        return view('shop.index', [
            'f' => $f, 'products' => $products, 'pageTitle' => $title,
            'path' => $path ?? trim('shop/'.$segment.'/'.$cat, '/'),
        ]);
    }

    public function accessories(Request $r)
    {
        return $this->index($r, force: ['accessory' => 1], path: 'accessories');
    }

    public function sale(Request $r)
    {
        return $this->index($r, force: ['sale' => 1], path: 'sale');
    }

    public function occasion(Request $r, string $slug)
    {
        return $this->index($r, force: ['occasion' => $slug], path: 'occasion/'.$slug);
    }

    public function search(Request $r)
    {
        return $this->index($r, path: 'search');
    }

    public function product(string $slug)
    {
        $p = Product::active()->with(['category', 'fabric', 'stitch', 'occasions', 'images', 'variants.color'])->where('slug', $slug)->firstOrFail();
        $seen = session('seen', []);
        if (! in_array($p->id, $seen)) {
            $p->increment('views');
            session(['seen' => [...$seen, $p->id]]);
        }

        $colors = $p->variants->groupBy('color_id')->map(fn ($vs) => [
            'id' => (int) $vs->first()->color_id,
            'name' => $vs->first()->color?->name ?? '',
            'hex' => $vs->first()->color?->hex ?: '#E8DCC2',
            'sizes' => $vs->map(fn ($v) => ['size' => $v->size, 'stock' => (int) $v->stock])->values(),
        ])->sortBy(fn ($c) => $p->variants->firstWhere('color_id', $c['id'])->color?->sort ?? 0)->values();

        $related = Product::active()->with(self::WITH_CARD)->where('category_id', $p->category_id)->whereKeyNot($p->id)->limit(4)->get();

        return view('shop.product', compact('p', 'colors', 'related'));
    }

    public function placeholder(string $hex, string $kind)
    {
        $kind = in_array($kind, ['hood', 'caftan', 'cap', 'shoe'], true) ? $kind : 'hood';

        return response(Placeholder::svg($hex, $kind), 200, ['Content-Type' => 'image/svg+xml', 'Cache-Control' => 'public, max-age=31536000']);
    }

    public function sitemap()
    {
        $urls = collect(['', 'shop', 'sale', 'about', 'contact'])->map(fn ($u) => url($u))
            ->merge([url('culture'), url('size-guide')])
            ->merge(Product::active()->pluck('slug')->map(fn ($s) => url('p/'.$s)))
            ->merge(Article::published()->pluck('slug')->map(fn ($s) => url('culture/'.$s)));
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            .$urls->map(fn ($u) => '<url><loc>'.e($u).'</loc></url>')->implode('').'</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function lang(Request $r, string $l)
    {
        if (in_array($l, ['ar', 'fr'], true)) {
            Cookie::queue('lang', $l, 60 * 24 * 365);
        }

        return redirect()->back(fallback: '/');
    }
}
