<?php
namespace App\Twig;

use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    public function __construct(
        private ContainerInterface $container
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('routeExists', [$this, 'routeExists']),
        ];
    }

    public function routeExists($name): bool
    {
        $router = $this->container->get('router');

        return !((null === $router->getRouteCollection()->get($name)));
    }
}
