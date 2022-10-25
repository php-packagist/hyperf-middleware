<?php

declare(strict_types=1);

namespace PhpPackagist\HyperfMiddleware;

use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Slow log middleware.
 */
class SlowLogMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * slow log threshold.
     *
     * @var float
     */
    protected float $threshold = 0.5;

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
        $startTime = microtime(true);

        defer(function () use ($request, $startTime) {
            $time = microtime(true) - $startTime;

            if ($time > $this->getThreshold()) {
                try {
                    $this->record($request, $time);
                } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
                    // do nothing
                }
            }
        });

        return $handler->handle($request);
    }

    /**
     * record slow log.
     *
     * @param ServerRequestInterface $request
     * @param float                  $time
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function record(ServerRequestInterface $request, float $time): void
    {
        $this->getLogger()->warning($this->getContent($request, $time));
    }

    /**
     * get log content.
     *
     * @param ServerRequestInterface $request
     * @param float                  $time
     *
     * @return string
     */
    protected function getContent(ServerRequestInterface $request, float $time): string
    {
        return sprintf(
            'Slow log: %s %s %s %s',
            $request->getMethod(),
            $request->getUri()->getPath(),
            $request->getUri()->getQuery(),
            $time
        );
    }

    /**
     * get logger.
     *
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->container->get(LoggerFactory::class)->get('slow_log');
    }

    /**
     * set slow log threshold.
     *
     * @return float
     */
    protected function getThreshold(): float
    {
        return $this->threshold;
    }
}
