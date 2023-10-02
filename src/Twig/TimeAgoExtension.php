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

    public function timeAgo(int $seconds): string
    {
        $request = $this->request->getCurrentRequest();
        $locale = $request->getLocale();
        $seck = round($seconds);
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seck");
    
        $formats = [
            'fr' => '%a jour(s), %h heure(s), %i minute(s), %s seconde(s)',
            'en' => '%a day(s), %h hour(s), %i minute(s), %s second(s)',
            'es' => '%a dia(s), %h hora(s), %i minuto(s), %s segundo(s)',
        ];
    
        return $dtF->diff($dtT)->format($formats[$locale] ?? '%a day(s), %h hour(s), %i minute(s), %s second(s)');
    }
}
