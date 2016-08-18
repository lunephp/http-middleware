<?php


namespace Lune\Http\Middleware\Tests\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lune\Http\Middleware\FrameInterface;
use Lune\Http\Middleware\MiddlewareInterface;

class TestMiddleware implements MiddlewareInterface
{

    public function handle(ServerRequestInterface $request, FrameInterface $next, array $parameters = []):ResponseInterface
    {
        return $next;
    }
}