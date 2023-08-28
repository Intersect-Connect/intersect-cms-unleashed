<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;


class NewsCategoryColorExtension extends AbstractExtension
{
    public function getFilters():array
    {
        return [
            new TwigFilter('categoryColor', [$this, 'timeAgo']),
             new TwigFilter('agoTwo', [$this, 'timeAgoTwo']),
        ];
    }

    public function timeAgo($color)
    {
       list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        return $color_name = "" . $r . "," . $g . "," . $b . ",80%";
    }
}
