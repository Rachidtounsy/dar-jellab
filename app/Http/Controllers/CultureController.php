<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;

class CultureController extends Controller
{
    public function index()
    {
        $topic = request('topic');
        $articles = Article::published()->when($topic, fn ($q, $t) => $q->where('topic', $t))->paginate(9)->withQueryString();

        return view('culture.index', compact('articles', 'topic'));
    }

    public function show(string $slug)
    {
        $a = Article::published()->where('slug', $slug)->firstOrFail();
        $more = Article::published()->whereKeyNot($a->id)->limit(3)->get();
        // produits liés à la thématique (mariage, henné, fêtes…)
        $occ = ['wedding' => 'wedding', 'henna' => 'henna', 'eid' => 'eid-fitr', 'ramadan' => 'ramadan'][$a->topic] ?? null;
        $products = $occ ? Product::active()->with(['category', 'images', 'variants.color'])->filter(['occasion' => $occ])->limit(4)->get() : collect();

        return view('culture.show', compact('a', 'more', 'products', 'occ'));
    }
}
