<?php


namespace Lune\Http\Middleware\Tests;

use League\Container\Container;
use PHPUnit_Framework_TestCase;
use Lune\Http\Middleware\MiddlewareInterface;
use Lune\Http\Middleware\Resolver\ContainerResolver;
use Lune\Http\Middleware\Resolver\StandardResolver;
use Lune\Http\Middleware\ResolverInterface;
use Lune\Http\Middleware\Tests\Middleware\TestMiddleware;

class ResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testStandardResolver()
    {
        $resolver = new StandardResolver();
        $this->runResolverTests($resolver);
    }


    /**
     * @test
     */
    public function testContainerResolver()
    {
        $container = new Container();

        $container->add(MiddlewareInterface::class, TestMiddleware::class);
        $resolver = new ContainerResolver($container);
        $this->runResolverTests($resolver);

        //resolve from container
        $middleware = $resolver->resolve(MiddlewareInterface::class);
        $this->assertInstanceOf(TestMiddleware::class, $middleware);

    }

    private function runResolverTests(ResolverInterface $resolver)
    {

        //resolve from string
        $middleware = $resolver->resolve(TestMiddleware::class);
        $this->assertInstanceOf(TestMiddleware::class, $middleware);

        //resolve from object
        $middleware = $resolver->resolve(new TestMiddleware());
        $this->assertInstanceOf(TestMiddleware::class, $middleware);

        //resolve from callable
        $middleware = $resolver->resolve(function () {
            return new TestMiddleware();
        });
        $this->assertInstanceOf(TestMiddleware::class, $middleware);
    }
}