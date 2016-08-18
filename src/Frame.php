<?php


namespace Lune\Http\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Frame implements FrameInterface
{

    /**
     * @var StackInterface
     */
    private $stack;
    private $index;

    public function __construct(StackInterface $stack, int $index)
    {
        $this->stack = $stack;
        $this->index = $index;

    }

    public function handle(RequestInterface $request):ResponseInterface
    {
        $handler = $this->stack->get($this->index);
        return $handler->handle($request, $this->next(), $this->stack->getParameters());
    }

    private function next()
    {
        $next = clone $this;
        $next->index += 1;
        return $next;
    }
}