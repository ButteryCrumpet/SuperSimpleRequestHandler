<?php

namespace SuperSimpleRequestHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LegacyHandler
{
    private $stack;

    /**
     * Handler constructor.
     * @param Iterable|array $stack Iterable or array of PSR-15 compliant middleware
     */
    public function __construct($stack)
    {
        if (! is_iterable($stack)) {
            throw new \InvalidArgumentException('\$queue must be array or Traversable.');
        }
        $this->stack = $stack;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
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