<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = [];

    public const STATUSES = [
        'new' => 'جديد', 'confirmed' => 'مؤكد', 'shipped' => 'تم الشحن', 'delivered' => 'تم التسليم', 'cancelled' => 'ملغى', 'returned' => 'مرتجع',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $o) {
            $o->ref ??= strtoupper(Str::random(8));
        });
        // annulation / retour : le stock est remis
        static::updated(function (self $o) {
            if ($o->wasChanged('status')) {
                $was = $o->getOriginal('status');
                $out = ['cancelled', 'returned'];
                if (in_array($o->status, $out) && ! in_array($was, $out)) {
                    $o->restock(1);
                } elseif (! in_array($o->status, $out) && in_array($was, $out)) {
                    $o->restock(-1);
                }
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function restock(int $sign): void
    {
        foreach ($this->items as $i) {
            if ($i->variant_id) {
                Variant::whereKey($i->variant_id)->increment('stock', $sign * $i->qty);
            }
        }
    }

    public function getNumberAttribute(): string
    {
        return 'DJ-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /** numéro marocain → format international sans + (212XXXXXXXXX) */
    public function getWaPhoneAttribute(): string
    {
        $p = preg_replace('/\D+/', '', $this->phone);
        $p = preg_replace('/^00/', '', $p);

        return str_starts_with($p, '212') ? $p : '212'.ltrim($p, '0');
    }

    /** résumé texte (WhatsApp) */
    public function summary(string $hello = ''): string
    {
        $lines = [$hello ?: 'طلب جديد '.$this->number, ''];
        foreach ($this->items as $i) {
            $lines[] = '• '.$i->name.($i->color ? ' / '.$i->color : '').($i->size ? ' / '.$i->size : '').($i->made_to_measure ? ' (على المقاس)' : '').' × '.$i->qty.' = '.number_format($i->price * $i->qty, 0, '.', ' ').' د.م';
        }
        $lines[] = '';
        $lines[] = 'المجموع: '.number_format($this->subtotal, 0, '.', ' ').' د.م';
        $lines[] = 'التوصيل: '.($this->shipping ? number_format($this->shipping, 0, '.', ' ').' د.م' : 'مجاني');
        $lines[] = 'الإجمالي: '.number_format($this->total, 0, '.', ' ').' د.م';
        $lines[] = '';
        $lines[] = 'الاسم: '.$this->name;
        $lines[] = 'الهاتف: '.$this->phone;
        $lines[] = 'المدينة: '.$this->city;
        if ($this->address) {
            $lines[] = 'العنوان: '.$this->address;
        }
        if ($this->notes) {
            $lines[] = 'ملاحظة: '.$this->notes;
        }

        return implode("\n", $lines);
    }
}
