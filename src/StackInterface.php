<?php


namespace Lune\Http\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface StackInterface
{
    public function getResolver():ResolverInterface;

    public function setResolver(ResolverInterface $resolver);

    public function get(int $index):MiddlewareInterface;

    public function execute(RequestInterface $request, ResponseInterface $response, array $parameters = []);

    public function hasParameter($name):bool;

    public function getParameter($name, $default = null);

    public function setParameter($name, $value = null);

    public function removeParameter($name);

    public function getParameters():array;

}