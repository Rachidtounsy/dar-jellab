<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('t')) {
    function t(string $key, array $replace = []): string
    {
        return __('shop.'.$key, $replace);
    }
}
if (! function_exists('setting')) {
    function setting(string $key, string $default = ''): string
    {
        $v = Setting::all_()[$key] ?? '';

        return $v !== '' ? $v : $default;
    }
}
if (! function_exists('setting_l')) {
    function setting_l(string $key, string $default = ''): string
    {
        return setting($key.'_'.app()->getLocale(), setting($key.'_ar', $default));
    }
}
if (! function_exists('wa_number')) {
    function wa_number(): string
    {
        return preg_replace('/\D+/', '', setting('whatsapp1'));
    }
}
if (! function_exists('wa_link')) {
    function wa_link(string $msg = '', ?string $num = null): string
    {
        return 'https://wa.me/'.($num !== null ? preg_replace('/\D+/', '', $num) : wa_number()).($msg !== '' ? '?text='.rawurlencode($msg) : '');
    }
}
if (! function_exists('money')) {
    function money($n): string
    {
        return number_format((float) $n, 0, '.', ' ').' '.(app()->getLocale() === 'ar' ? 'د.م' : 'MAD');
    }
}
if (! function_exists('segment_label')) {
    function segment_label(string $s): string
    {
        return t('seg_'.$s);
    }
}
if (! function_exists('upload_url')) {
    function upload_url(?string $path): string
    {
        return $path ? Storage::disk('public')->url($path) : '';
    }
}
