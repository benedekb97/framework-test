<?php

declare(strict_types=1);

namespace App\Core\Handler;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class RequestHandler implements RequestHandlerInterface
{
    private UrlMatcherInterface $urlMatcher;

    private Request $request;

    public function __construct(
        UrlMatcher $urlMatcher
    )
    {
        $this->urlMatcher = $urlMatcher;
    }

    public function handle(Request $request): void
    {
        $this->initialize($request);

        try {
            $route = $this->urlMatcher->match($this->request->getPathInfo());
        } catch (ResourceNotFoundException) {
            $this->handle404();
        }

        if (array_key_exists('_controller', $route)) {
            [$controller, $method] = $this->resolveController($route);

            $controller = new $controller();

            if ($method === null) {
                $response = $controller($request);
            }

            if (is_string($method)) {
                $response = $controller->$method($request);
            }

            if (!isset($response) || !$response instanceof Response) {
                return;
                // throw exception
            }

            $response->send();
        }
    }

    private function handle404(): void
    {
        $response = new JsonResponse(
            [
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Page could not be found',
            ],
            Response::HTTP_NOT_FOUND
        );

        $response->send();
    }

    // Single responsibility principle
    private function initialize(Request $request): void
    {
        $this->request = $request;

        $context = new RequestContext;
        $context->fromRequest($request);
    }

    // move
    private function resolveController(array $route): array
    {
        $parts = explode('::', $route['_controller']);

        if (count($parts) === 1) {
            return [$route['_controller'], null];
        }

        if (count($parts) === 2) {
            return [$parts[0], $parts[1]];
        }

        return [];
    }
}