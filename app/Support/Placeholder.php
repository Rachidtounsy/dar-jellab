<?php

namespace App\Support;

/** Silhouette SVG de remplacement (sans photo), teintée de la couleur du produit. */
class Placeholder
{
    public static function svg(string $hex, string $kind): string
    {
        $hex = '#'.preg_replace('/[^0-9a-fA-F]/', '', $hex);
        [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x') + [0, 0, 0];
        $lum = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        $trim = $lum > 0.62 ? '#1F3A5F' : '#E3C874';
        $bg1 = '#F6F0E6';
        $bg2 = '#EADFCB';
        $stroke = $lum > 0.85 ? ' stroke="#D8CCB4" stroke-width="2"' : '';

        $shape = match ($kind) {
            'caftan' => "<path d='M150 50C130 50 118 62 112 80L66 112C54 120 50 134 52 150L58 200L96 196L92 372H208L204 196L242 200L248 150C250 134 246 120 234 112L188 80C182 62 170 50 150 50Z' fill='$hex'$stroke/>
                <path d='M126 68L150 128L174 68Z' fill='$bg1'/><path d='M150 128V372' stroke='$trim' stroke-width='5' stroke-dasharray='1 9' stroke-linecap='round'/>
                <rect x='92' y='214' width='116' height='14' fill='$trim'/><path d='M92 366H208' stroke='$trim' stroke-width='5'/><path d='M54 146L60 196' stroke='$trim' stroke-width='5'/><path d='M246 146L240 196' stroke='$trim' stroke-width='5'/>",
            'cap' => "<path d='M96 300L112 120C114 104 124 96 150 96C176 96 186 104 188 120L204 300Z' fill='$hex'$stroke/><path d='M150 96V60' stroke='$trim' stroke-width='6' stroke-linecap='round'/><circle cx='150' cy='54' r='9' fill='$trim'/>",
            'shoe' => "<path d='M50 300C50 250 90 230 130 238C176 246 214 250 250 280C256 296 246 312 228 312H66C56 312 50 308 50 300Z' fill='$hex'$stroke/><path d='M60 300H240' stroke='$trim' stroke-width='5'/><path d='M96 250C120 262 150 262 176 252' stroke='$trim' stroke-width='5' fill='none' stroke-linecap='round'/>",
            default => "<path d='M150 40C170 52 182 70 184 92L236 118C250 126 256 140 258 160L266 372H34L42 160C44 140 50 126 64 118L116 92C118 70 130 52 150 40Z' fill='$hex'$stroke/>
                <ellipse cx='150' cy='96' rx='20' ry='26' fill='$bg1'/><path d='M150 124V372' stroke='$trim' stroke-width='5' stroke-dasharray='1 9' stroke-linecap='round'/>
                <path d='M38 352H262' stroke='$trim' stroke-width='5'/><path d='M40 340H260' stroke='$trim' stroke-width='2'/><path d='M44 150L50 354' stroke='$trim' stroke-width='3' opacity='.6'/><path d='M256 150L250 354' stroke='$trim' stroke-width='3' opacity='.6'/>",
        };

        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 400'><defs><linearGradient id='g' x1='0' y1='0' x2='0' y2='1'><stop offset='0' stop-color='$bg1'/><stop offset='1' stop-color='$bg2'/></linearGradient></defs><rect width='300' height='400' fill='url(#g)'/>$shape</svg>";
    }
}
