<?php


namespace Lune\Http\Middleware;


interface ResolverInterface
{
    public function resolve($identifier):MiddlewareInterface;
}