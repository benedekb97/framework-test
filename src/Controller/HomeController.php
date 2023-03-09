<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return new JsonResponse(['code' => Response::HTTP_OK]);
    }

    public function lol(Request $request): Response
    {
        return new JsonResponse(['message' => 'lol', 'code' => Response::HTTP_OK]);
    }
}