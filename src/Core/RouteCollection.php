<?php

declare(strict_types=1);

namespace App\Core;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection as SymfonyRouteCollection;

class RouteCollection extends SymfonyRouteCollection
{
    public function __construct()
    {
        $yamlReader = new YamlFileLoader(
            new FileLocator(__DIR__ . '/../../config')
        );

        $routeCollection = $yamlReader->load('routing.yaml');

        /** @var Route $route */
        foreach ($routeCollection->getIterator() as $name => $route) {
            $this->add($name, $route);
        }
    }
}