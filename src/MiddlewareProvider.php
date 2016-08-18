<?php


namespace Lune\Http\Middleware;


use Lune\Http\Middleware\Exception\UnknownStackException;
use Lune\Http\Middleware\Resolver\HasResolverTrait;
use Lune\Http\Middleware\Resolver\StandardResolver;

class MiddlewareProvider
{
    use HasResolverTrait;

    private $stacks = [];

    public function __construct(ResolverInterface $resolver = null)
    {
        $this->setResolver($resolver??new StandardResolver());
    }

    public function addStack(string $name, $middlewares = [], $parameters = [])
    {
        $this->stacks[$name] = [
            'middlewares' => $middlewares,
            'parameters' => $parameters
        ];
    }

    public function getStackData(string $name):array
    {
        if (!array_key_exists($name, $this->stacks)) {
            throw new UnknownStackException($name);
        }
        return $this->stacks[$name];
    }

    public function getStack($names):Stack
    {
        $stack = new Stack();
        $stack->setResolver($this->getResolver());
        foreach ((array)$names as $name) {
            $data = $this->getStackData($name);
            array_map([$stack, 'append'], $data['middlewares']);
            $stack->setParameter($data['parameters']);
        }

        return $stack;
    }

}