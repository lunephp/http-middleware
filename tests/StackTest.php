<?php


namespace Lune\Http\Middleware\Tests;

use PHPUnit_Framework_TestCase;
use Lune\Http\Middleware\Middleware\CallableWrapper;
use Lune\Http\Middleware\Middleware\StackWrapper;
use Lune\Http\Middleware\MiddlewareProvider;
use Lune\Http\Middleware\Resolver\StandardResolver;
use Lune\Http\Middleware\Stack;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;

class StackTest extends PHPUnit_Framework_TestCase
{
    public function testCreateStack()
    {
        $stack = new Stack();
        $this->assertInstanceOf(StandardResolver::class, $stack->getResolver());
    }

    public function testStackParameters()
    {
        $stack = new Stack([], ['test' => 'ok']);

        $this->assertTrue($stack->hasParameter('test'));
        $this->assertEquals($stack->getParameter('test'), 'ok');

        $stack->setParameter('test', 'overwritten');
        $this->assertEquals($stack->getParameter('test'), 'overwritten');

        $stack->removeParameter('test');
        $this->assertFalse($stack->hasParameter('test'));

        $this->assertEquals($stack->getParameter('test', 'default'), 'default');
    }

    public function testStackRunner()
    {


        $stack = new Stack([]);

        $request = new ServerRequest();
        $response = $stack->execute($request, new HtmlResponse(""));

        $this->assertInstanceOf(HtmlResponse::class, $response);

        $stack->append(new CallableWrapper(function ($request, $next, $parameters) {
            $response = $next->handle($request);
            $response->getBody()->write('ok');
            return $response;
        }));

        $response = $stack->execute($request, new HtmlResponse(""));
        $this->assertEquals((string)$response->getBody(), "ok");
    }


    public function testMiddlewareProvider()
    {
        $provider = new MiddlewareProvider();
        $provider->addStack('test', [
            new CallableWrapper(function ($request, $next, $parameters) {
                $response = $next->handle($request);
                $response->getBody()->write(' cb_1');
                return $response;
            })
        ], ['test' => 'ok']);

        $provider->addStack('test_2', [
            new CallableWrapper(function ($request, $next, $parameters) {
                $response = $next->handle($request);
                $response->getBody()->write('cb_2');
                return $response;
            })
        ], ['test' => 'overwritten']);


        $stack = $provider->getStack(['test', 'test_2']);


        $request = new ServerRequest();
        $response = $stack->execute($request, new HtmlResponse(""));


        $this->assertEquals((string)$response->getBody(), 'cb_2 cb_1');
        $this->assertEquals($stack->getParameter('test'), 'overwritten');

    }

    public function testStackWrapper()
    {
        $wrapper = new StackWrapper(new Stack());
        $wrapper->getStack()->append(new CallableWrapper(function ($request, $next, $parameters) {
            $response = $next->handle($request);
            $response->getBody()->write('ok');
            return $response;
        }));

        $stack = new Stack();
        $stack->append($wrapper);

        $request = new ServerRequest();
        $response = $stack->execute($request, new HtmlResponse(""));

        $this->assertEquals((string)$response->getBody(), 'ok');
    }
}