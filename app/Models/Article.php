<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use Localized;

    protected $guarded = [];

    protected $casts = ['published' => 'boolean', 'published_at' => 'datetime'];

    public const TOPICS = [
        'wedding' => 'العرس', 'henna' => 'الحنّاء', 'eid' => 'الأعياد', 'ramadan' => 'رمضان', 'craft' => 'الحرفة والخياطة', 'north' => 'الشمال', 'other' => 'أخرى',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $a) {
            if (! $a->slug) {
                $base = Str::slug($a->title_fr ?: $a->title_ar) ?: Str::random(6);
                $slug = $base;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.random_int(10, 99);
                }
                $a->slug = $slug;
            }
            $a->published_at ??= now();
        });
    }

    public function scopePublished($q)
    {
        return $q->where('published', true)->where('published_at', '<=', now())->orderByDesc('published_at');
    }

    public function getTitleAttribute(): string
    {
        return $this->localized('title');
    }

    public function getExcerptTextAttribute(): string
    {
        return $this->localized('excerpt');
    }

    public function getBodyHtmlAttribute(): string
    {
        return $this->localized('body');
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover ? Storage::disk('public')->url($this->cover) : null;
    }

    public function getTopicLabelAttribute(): string
    {
        return t('topic_'.$this->topic);
    }
}
