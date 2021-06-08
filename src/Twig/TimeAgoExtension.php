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

        // $timestamp = strtotime($date);

        // $strTime = array("second", "minute", "hour", "day", "month", "year");
        // $length = array("60", "60", "24", "30", "12", "10");

        // $currentTime = time();
        // if ($currentTime >= $timestamp) {
        //     $diff     = time() - $timestamp;
        //     for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
        //         $diff = $diff / $length[$i];
        //     }

        //     $diff = round($diff);
        //     return $diff . " " . $strTime[$i] . "(s) ago ";
        // }
    }
}
