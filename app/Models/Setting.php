<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }

    /** @return array<string,string> */
    public static function all_(): array
    {
        return Cache::rememberForever('settings', fn () => static::query()->pluck('value', 'key')->all());
    }

    public static function put(array $values): void
    {
        foreach ($values as $k => $v) {
            static::query()->updateOrInsert(['key' => $k], ['value' => $v ?? '']);
        }
        Cache::forget('settings');
    }
}
