<?php


namespace Lune\Http\Middleware\Resolver;


use Lune\Http\Middleware\ResolverInterface;

trait HasResolverTrait
{
    private $resolver;

    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }


    public function getResolver():ResolverInterface
    {
        if (is_null($this->resolver)) {
            $this->resolver = new StandardResolver();
        }
        return $this->resolver;
    }
}