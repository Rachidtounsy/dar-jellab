<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use Localized;

    protected $guarded = [];

    protected $casts = ['made_to_measure' => 'boolean', 'featured' => 'boolean', 'active' => 'boolean'];

    public const BADGES = ['new' => 'جديد', 'sale' => 'تخفيض', 'bestseller' => 'الأكثر مبيعا', 'exclusive' => 'حصري'];

    protected static function booted(): void
    {
        static::creating(function (self $p) {
            if (! $p->slug) {
                $base = Str::slug($p->name_fr ?: $p->name_ar) ?: Str::random(6);
                $slug = $base;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.random_int(10, 99);
                }
                $p->slug = $slug;
            }
        });
        // le segment suit toujours la catégorie
        static::saving(function (self $p) {
            if ($p->category_id) {
                $p->segment = Category::whereKey($p->category_id)->value('segment') ?? $p->segment;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fabric()
    {
        return $this->belongsTo(Term::class, 'fabric_id');
    }

    public function stitch()
    {
        return $this->belongsTo(Term::class, 'stitch_id');
    }

    public function occasions()
    {
        return $this->belongsToMany(Term::class, 'product_term')->where('type', 'occasion');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort')->orderBy('id');
    }

    public function getOnSaleAttribute(): bool
    {
        return $this->old_price && $this->old_price > $this->price;
    }

    public function getDiscountAttribute(): int
    {
        return $this->on_sale ? (int) round(100 - $this->price / $this->old_price * 100) : 0;
    }

    /** URL de l'image principale, ou silhouette SVG teintée si aucune photo */
    public function getImageAttribute(): string
    {
        $img = $this->relationLoaded('images') ? $this->images->first() : $this->images()->first();
        if ($img) {
            return $img->url;
        }
        $hex = ltrim($this->relationLoaded('variants') ? ($this->variants->pluck('color.hex')->filter()->first() ?? '#E8DCC2') : '#E8DCC2', '#');
        $slug = $this->category?->slug;
        $kind = match (true) {
            in_array($slug, ['caftan', 'takchita']) => 'caftan',
            $slug === 'tarbouch' => 'cap',
            $slug === 'belgha' => 'shoe',
            default => 'hood',
        };

        return route('placeholder', [$hex, $kind]);
    }

    public function colorsList()
    {
        return $this->variants->pluck('color')->filter()->unique('id')->sortBy('sort')->values();
    }

    /* ---- Filtres du catalogue ---- */
    public function scopeActive(Builder $q)
    {
        return $q->where('products.active', true);
    }

    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['segment'] ?? null, fn ($q, $v) => $q->where('products.segment', $v))
            ->when($f['cat'] ?? null, fn ($q, $v) => $q->whereHas('category', fn ($c) => $c->where('slug', $v)))
            ->when($f['accessory'] ?? null, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('accessory', true)))
            ->when($f['sale'] ?? null, fn ($q) => $q->whereColumn('old_price', '>', 'price'))
            ->when($f['fabric'] ?? null, fn ($q, $v) => $q->whereHas('fabric', fn ($t) => $t->where('slug', $v)))
            ->when($f['stitch'] ?? null, fn ($q, $v) => $q->whereHas('stitch', fn ($t) => $t->where('slug', $v)))
            ->when($f['color'] ?? null, fn ($q, $v) => $q->whereHas('variants.color', fn ($t) => $t->where('slug', $v)))
            ->when($f['occasion'] ?? null, fn ($q, $v) => $q->whereHas('occasions', fn ($t) => $t->where('slug', $v)))
            ->when($f['min'] ?? null, fn ($q, $v) => $q->where('price', '>=', (int) $v))
            ->when($f['max'] ?? null, fn ($q, $v) => $q->where('price', '<=', (int) $v))
            ->when($f['q'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w->where('name_ar', 'like', "%$v%")->orWhere('name_fr', 'like', "%$v%")
                ->orWhereHas('category', fn ($c) => $c->where('name_ar', 'like', "%$v%")->orWhere('name_fr', 'like', "%$v%"))));
    }

    public function scopeSortBy(Builder $q, ?string $s): Builder
    {
        return match ($s) {
            'price_asc' => $q->orderBy('price'),
            'price_desc' => $q->orderByDesc('price'),
            'popular' => $q->orderByDesc('views')->orderByDesc('id'),
            default => $q->orderByDesc('id'),
        };
    }
}
