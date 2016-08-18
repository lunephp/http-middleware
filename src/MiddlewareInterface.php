<?php


namespace Lune\Http\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    public function handle(RequestInterface $request, FrameInterface $next, array $parameters = []):ResponseInterface;
}