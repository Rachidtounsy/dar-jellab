<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use Localized;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['accessory' => 'boolean', 'active' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (! $m->slug) {
                $base = Str::slug($m->name_fr ?: $m->name_ar) ?: Str::random(5);
                $slug = $base;
                while (static::where('segment', $m->segment)->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.random_int(10, 99);
                }
                $m->slug = $slug;
            }
        });
    }

    public const SEGMENTS = ['men', 'women', 'boys', 'girls'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($q)
    {
        return $q->where('active', true);
    }
}
