<?php

namespace SuperSimpleRequestHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Handler implements RequestHandlerInterface
{
    private $stack;

    /**
     * Handler constructor.
     * @param Iterable|array $stack Iterable or array of PSR-15 compliant middleware
     */
    public function __construct($stack)
    {
        if (! is_iterable($stack)) {
            throw new \InvalidArgumentException('the Stack must be array or Traversable.');
        }
        $this->stack = $stack;
    }

    /**
     * @inheritdoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $current = current($this->stack);
        if (!$current) {
            throw new \RuntimeException(
                "Final element in stack must return a Response"
            );
        }
        next($this->stack);
        return $current->process($request, $this);
    }

}
