<?php


namespace Lune\Http\Middleware\Resolver;


use Interop\Container\ContainerInterface;

class ContainerResolver extends StandardResolver
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getContainer():ContainerInterface
    {
        return $this->container;
    }

    protected function resolveFromString($identifier)
    {
        if ($this->getContainer()->has($identifier)) {
            //get the definition from the container, then run it through the resolver again
            return $this->resolve($this->getContainer()->get($identifier));
        }
        return parent::resolveFromString($identifier);
    }

}