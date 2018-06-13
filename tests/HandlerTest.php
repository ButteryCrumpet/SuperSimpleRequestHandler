<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleRequestHandler\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class HandlerTest extends TestCase
{
    public function testInitializes()
    {
        $this->assertInstanceOf(
            Handler::class,
            new Handler([])
        );
    }

    public function testThrowsExceptionOnInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Handler(0);
    }

    public function testReturnsResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method("getStatusCode")->willReturn(200);
        $middleware1 = $this->createMock(MiddlewareInterface::class);
        $middleware2 = $this->createMock(MiddlewareInterface::class);
        $middleware2
            ->method("process")
            ->willReturn($response);

        $handler = new Handler([$middleware1, $middleware2]);

        $middleware1
            ->method("process")
            ->willReturn($handler->handle($request));

        $resp = $handler->handle($request);
        $this->assertInstanceOf(
            ResponseInterface::class, $resp
        );
        $this->assertEquals(200, $resp->getStatusCode());
    }

    public function testThrowsExceptionIfNoResponseReturned()
    {
        $this->expectException(\RuntimeException::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $middleware1 = $this->createMock(MiddlewareInterface::class);
        $middleware2 = $this->createMock(MiddlewareInterface::class);
        $handler = new Handler([$middleware1, $middleware2]);
        $middleware1
            ->method("process")
            ->willReturn($handler->handle($request));
        $middleware2
            ->method("process")
            ->willReturn($handler->handle($request));
        $handler->handle($request);
    }
}

