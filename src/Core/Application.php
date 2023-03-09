<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Handler\RequestHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpFoundation\Request;

class Application
{
    private Request $request;

    private ContainerBuilder $container;

    private RequestHandlerInterface $requestHandler;

    public function run(): void
    {
        $this->initialize();

        $this->requestHandler->handle($this->request);
    }

    private function initialize(): void
    {
        $this->initializeContainer();
        $this->initializeRequest();
        $this->initializeRequestHandler();
    }

    private function initializeRequest(): void
    {
        $this->request = Request::createFromGlobals();
    }

    private function initializeContainer(): void
    {
        // autowiring?
        $this->container = new ContainerBuilder();

        $loader = new XmlFileLoader($this->container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');
    }

    private function initializeRequestHandler(): void
    {
        $requestHandler = $this->container->get('request_handler');

        if (!$requestHandler instanceof RequestHandlerInterface) {
            throw new \InvalidArgumentException(
                sprintf('Request handler must implement %s', RequestHandlerInterface::class)
            );
        }

        $this->requestHandler = $requestHandler;
    }
}