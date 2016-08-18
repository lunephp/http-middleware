<?php


namespace Lune\Http\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    public function handle(ServerRequestInterface $request, FrameInterface $next, array $parameters = []):ResponseInterface;
}