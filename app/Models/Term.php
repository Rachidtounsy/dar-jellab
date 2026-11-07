<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Term extends Model
{
    use Localized;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['active' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (! $m->slug) {
                $base = Str::slug($m->name_fr ?: $m->name_ar) ?: Str::random(5);
                $slug = $base;
                while (static::where('type', $m->type)->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.random_int(10, 99);
                }
                $m->slug = $slug;
            }
        });
    }

    public const TYPES = ['color' => 'الألوان', 'fabric' => 'الأثواب', 'stitch' => 'أنواع الخياطة', 'occasion' => 'المناسبات'];

    public function scopeOfType($q, string $type)
    {
        return $q->where('type', $type)->where('active', true)->orderBy('sort')->orderBy('id');
    }
}
