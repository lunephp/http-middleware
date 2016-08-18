<?php


namespace Lune\Http\Middleware\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lune\Http\Middleware\FrameInterface;
use Lune\Http\Middleware\MiddlewareInterface;

use Lune\Http\Middleware\StackInterface;

class StackWrapper implements MiddlewareInterface
{

    private $stack;

    public function __construct(StackInterface $stack)
    {
        $this->setStack($stack);
    }

    public function getStack():StackInterface
    {
        return $this->stack;
    }

    public function setStack(StackInterface $stack)
    {
        $this->stack = $stack;
    }

    public function handle(ServerRequestInterface $request, FrameInterface $next, array $parameters = []):ResponseInterface
    {
        return $this->getStack()->execute($request, $next->handle($request), $parameters);
    }
}