<?php


namespace Lune\Http\Middleware\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lune\Http\Middleware\FrameInterface;
use Lune\Http\Middleware\MiddlewareInterface;


class ResponseProvider implements MiddlewareInterface
{
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request, FrameInterface $next, array $parameters = []):ResponseInterface
    {
        return $this->response;
    }
}