<?php

declare(strict_types=1);

namespace App\Core\Handler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{
    public function handle(Request $request): void;
}