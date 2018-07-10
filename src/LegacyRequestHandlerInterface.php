<?php

namespace SuperSimpleRequestHandler;

use Psr\Http\Message\ServerRequestInterface;

/**
 * An HTTP request handler process a HTTP request and produces an HTTP response.
 * This interface defines the methods require to use the request handler.
 */
interface LegacyRequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface $response
     */
    public function handle(ServerRequestInterface $request);
}
