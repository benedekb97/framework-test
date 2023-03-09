<?php

declare(strict_types=1);

namespace App\Core;

use Symfony\Component\HttpFoundation\Request;

class RequestContext extends \Symfony\Component\Routing\RequestContext
{
    public function __construct(string $baseUrl = '', string $method = 'GET', string $host = 'localhost', string $scheme = 'http', int $httpPort = 80, int $httpsPort = 443, string $path = '/', string $queryString = '')
    {
        parent::__construct($baseUrl, $method, $host, $scheme, $httpPort, $httpsPort, $path, $queryString);

        $this->fromRequest(Request::createFromGlobals());
    }
}