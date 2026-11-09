<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;
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

    /** Corps HTML nettoyé : balises autorisées uniquement, sans attributs d'événements ni liens javascript:. */
    public function getBodyHtmlAttribute(): string
    {
        $allowed = '<p><br><h2><h3><h4><ul><ol><li><strong><b><em><i><u><s><a><blockquote><img><figure><figcaption><hr><table><thead><tbody><tr><td><th>';
        $html = strip_tags($this->localized('body'), $allowed);
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);

        return preg_replace('/(href|src)\s*=\s*(["\'])\s*(javascript|data|vbscript):[^"\']*\2/i', '$1=$2#$2', $html);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover ? asset('storage/'.$this->cover) : null;
    }

    public function getTopicLabelAttribute(): string
    {
        return t('topic_'.$this->topic);
    }
}
