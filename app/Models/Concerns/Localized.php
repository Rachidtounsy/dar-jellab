<?php

namespace App\Models\Concerns;

/** name_ar / name_fr  →  $model->name (selon la langue courante) */
trait Localized
{
    public function localized(string $field): string
    {
        $loc = app()->getLocale();

        return (string) (($this->{$field.'_'.$loc} ?? '') ?: ($this->{$field.'_ar'} ?? ($this->{$field.'_fr'} ?? '')));
    }

    public function getNameAttribute(): string
    {
        return $this->localized('name');
    }
}
