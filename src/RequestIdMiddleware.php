<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Hyperf\Context\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

/**
 * Request id middleware.
 */
class RequestIdMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * define the request id header name.
     *
     * @var string
     */
    protected string $headerKey = 'X-Request-Id';

    /**
     * define the request id context key.
     *
     * @var string
     */
    protected string $contextKey = 'x-request-id';

    /**
     * create a new request id middleware instance.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * process the request and response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestId = $request->getHeader($this->headerKey) ?: $this->generateRequestId();

        $response = Context::get(ResponseInterface::class);
        $response = $response->withAddedHeader($this->headerKey, $requestId);

        Context::set(ResponseInterface::class, $response);
        Context::set($this->contextKey, $requestId);

        return $handler->handle($request);
    }

    /**
     * generate a unique request id.
     *
     * @return string
     */
    public function generateRequestId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
