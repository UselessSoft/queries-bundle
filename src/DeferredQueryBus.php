<?php

declare(strict_types=1);

namespace UselessSoft\QueriesBundle;

use Kdyby\StrictObjects\Scream;
use Symfony\Component\DependencyInjection\ServiceLocator;
use UselessSoft\Queries\Exception\InvalidArgumentException;
use UselessSoft\Queries\QueryBusInterface;
use UselessSoft\Queries\QueryHandlerInterface;
use UselessSoft\Queries\QueryInterface;

class DeferredQueryBus implements QueryBusInterface
{
    use Scream;

    /** @var ServiceLocator */
    private $locator;

    /** @var string[] */
    private $handlerNames;

    /**
     * @param string[] $handlerNames
     */
    public function __construct(ServiceLocator $locator, array $handlerNames)
    {
        $this->locator = $locator;
        $this->handlerNames = $handlerNames;
    }

    public function supports(QueryInterface $query) : bool
    {
        return $this->resolveHandler($query) !== null;
    }

    public function handle(QueryInterface $query)
    {
        $handler = $this->resolveHandler($query);

        if ($handler === null) {
            throw new InvalidArgumentException('Unsupported query.');
        }

        return $handler->handle($query);
    }

    private function resolveHandler(QueryInterface $query) : ?QueryHandlerInterface
    {
        foreach ($this->handlerNames as $handlerName) {
            /** @var QueryHandlerInterface $handler */
            $handler = $this->locator->get($handlerName);

            if ($handler->supports($query)) {
                return $handler;
            }
        }

        return null;
    }
}
