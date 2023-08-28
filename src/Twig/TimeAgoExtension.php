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
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;

class TimeAgoExtension extends AbstractExtension
{
        private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack;
    }

    public function getFilters():array
    {
        return [
            new TwigFilter('ago', [$this, 'timeAgo']),
            new TwigFilter('agoTwo', [$this, 'timeAgoTwo']),
        ];
    }

    public function timeAgo($seconds)
    {
        $request = $this->request->getCurrentRequest();
        $locale = $request->getLocale();
        $seck = round($seconds);
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seck");

        switch ($locale) {
            case "fr":
                return $dtF->diff($dtT)->format('%a jour(s), %h heure(s), %i minute(s), %s seconde(s)');
                break;
            case "en":
                return $dtF->diff($dtT)->format('%a day(s), %h hour(s), %i minute(s), %s second(s)');
                break;
            case "es":
                return $dtF->diff($dtT)->format('%a dia(s), %h hora(s), %i minuto(s), %s segundo(s)');
                break;
                default:
        return $dtF->diff($dtT)->format('%a day(s), %h hour(s), %i minute(s), %s second(s)');
        }
    }
}
