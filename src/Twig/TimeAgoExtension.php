<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeAgoExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('ago', [$this, 'timeAgo']),
        ];
    }

    public function timeAgo($seconds)
    {
        $seck = round($seconds / 1000);
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seck");
        return $dtF->diff($dtT)->format('%a jour(s), %h heure(s), %i minute(s)');
    }
}
