<?php


namespace Lune\Http\Middleware\Resolver;

use Lune\Http\Middleware\Exception\CannotResolveException;
use Lune\Http\Middleware\MiddlewareInterface;
use Lune\Http\Middleware\ResolverInterface;

class StandardResolver implements ResolverInterface
{
    public function resolve($identifier):MiddlewareInterface
    {
        if (is_object($identifier) && ($identifier instanceof MiddlewareInterface)) {
            return $identifier;
        }

        $middleware = null;
        if (is_callable($identifier)) {
            $middleware = $this->resolveFromCallable($identifier);
        } else if (is_string($identifier)) {
            $middleware = $this->resolveFromString($identifier);
        }

        if (!$middleware instanceof MiddlewareInterface) {
            throw new CannotResolveException($identifier);
        }

        return $middleware;
    }

    protected function resolveFromString($identifier)
    {
        if (class_exists($identifier)) {
            return new $identifier;
        }
    }

    protected function resolveFromCallable($identifier)
    {
        return $identifier();
    }
}