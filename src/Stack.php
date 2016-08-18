<?php


namespace Lune\Http\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use OutOfRangeException;
use Lune\Http\Middleware\Middleware\ResponseProvider;
use Lune\Http\Middleware\Resolver\HasResolverTrait;
use Lune\Http\Middleware\Resolver\StandardResolver;

class Stack implements StackInterface
{

    use HasResolverTrait;

    private $stack = [];
    private $parameters = [];

    public function __construct($stack = [], $parameters = [], ResolverInterface $resolver = null)
    {
        $this->setResolver($resolver??new StandardResolver());
        $this->parameters = $parameters;
        array_map([$this, "append"], $stack);
    }

    public function append($identifier)
    {
        array_push($this->stack, $identifier);
    }

    public function prepend($identifier)
    {
        array_unshift($this->stack, $identifier);
    }



    public function get(int $index):MiddlewareInterface
    {
        if (array_key_exists($index, $this->stack)) {
            return $this->getResolver()->resolve($this->stack[$index]);
        }

        throw new OutOfRangeException("Invalid index: {$index}");
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response, array $parameters = [])
    {
        $stack = clone $this;
        $stack->setParameter($parameters);
        $stack->append(new ResponseProvider($response));
        $frame = new Frame($stack, 0);
        return $frame->handle($request);
    }

    public function hasParameter($name):bool
    {
        return array_key_exists($name, $this->parameters);
    }

    public function getParameter($name, $default = null)
    {
        return $this->hasParameter($name) ? $this->parameters[$name] : $default;
    }

    public function setParameter($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $sub_name => $sub_value) {
                $this->setParameter($sub_name, $sub_value);
            }
        } else {
            $this->parameters[$name] = $value;
        }
    }

    public function removeParameter($name)
    {
        foreach ((array)$name as $sub_name) {
            if ($this->hasParameter($sub_name)) {
                unset($this->parameters[$sub_name]);
            }
        }
    }

    public function getParameters():array
    {
        return $this->parameters;
    }
}