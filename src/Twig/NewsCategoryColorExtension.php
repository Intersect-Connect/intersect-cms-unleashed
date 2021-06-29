<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class NewsCategoryColorExtension extends AbstractExtension
{
    public function getFilters()
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
