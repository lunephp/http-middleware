<?php


namespace Lune\Http\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FrameInterface
{
    public function __construct(StackInterface $stack, int $index);

    public function handle(RequestInterface $request):ResponseInterface;
}