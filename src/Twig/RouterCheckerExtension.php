<?php

/**
 * Intersect CMS Unleashed
 * 2.3 Update
 * Last modify : 15/04/2022 ay 10:16
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;


class RouterCheckerExtension extends AbstractExtension
{
    private $request;
    private $urlGenerator;


    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $urlGenerator)
    {
        $this->request = $requestStack;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('checkRoute', [$this, 'check']),
        ];
    }

    public function check($route)
    {
        if ($route !== null) {
            try {
                $url = $this->urlGenerator->generate($route);
                return true;
            } catch (RouteNotFoundException $notfound) {
                return false;
            }
        }
    }
}
