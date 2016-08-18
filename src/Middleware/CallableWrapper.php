<?php


namespace Lune\Http\Middleware\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lune\Http\Middleware\FrameInterface;
use Lune\Http\Middleware\MiddlewareInterface;

class CallableWrapper implements MiddlewareInterface
{

    private $callable;

    public function __construct(callable  $callable)
    {
        $this->callable = $callable;
    }

    public function handle(RequestInterface $request, FrameInterface $next, array $parameters = []):ResponseInterface
    {
        return call_user_func_array($this->callable, [$request, $next, $parameters]);
    }
}