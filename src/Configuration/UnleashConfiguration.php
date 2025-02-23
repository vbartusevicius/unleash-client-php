<?php

namespace Unleash\Client\Configuration;

use JetBrains\PhpStorm\Pure;
use LogicException;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Unleash\Client\Bootstrap\BootstrapHandler;
use Unleash\Client\Bootstrap\BootstrapProvider;
use Unleash\Client\Bootstrap\DefaultBootstrapHandler;
use Unleash\Client\Bootstrap\EmptyBootstrapProvider;
use Unleash\Client\ContextProvider\DefaultUnleashContextProvider;
use Unleash\Client\ContextProvider\UnleashContextProvider;

final class UnleashConfiguration
{
    /**
     * @param array<string,string> $headers
     */
    public function __construct(
        private string $url,
        private string $appName,
        private string $instanceId,
        private ?CacheInterface $cache = null,
        private int $ttl = 30,
        private int $metricsInterval = 30_000,
        private bool $metricsEnabled = true,
        private array $headers = [],
        private bool $autoRegistrationEnabled = true,
        private UnleashContextProvider $contextProvider = new DefaultUnleashContextProvider(),
        private BootstrapHandler $bootstrapHandler = new DefaultBootstrapHandler(),
        private BootstrapProvider $bootstrapProvider = new EmptyBootstrapProvider(),
        private bool $fetchingEnabled = true,
        private EventDispatcherInterface $eventDispatcher = new EventDispatcher(),
        private int $staleTtl = 30 * 60,
        private ?CacheInterface $staleCache = null,
        private ?string $proxyKey = null,
    ) {
        $this->contextProvider ??= new DefaultUnleashContextProvider();
    }

    public function getCache(): CacheInterface
    {
        if ($this->cache === null) {
            throw new LogicException('Cache handler is not set');
        }

        return $this->cache;
    }

    public function getStaleCache(): CacheInterface
    {
        return $this->staleCache ?? $this->getCache();
    }

    public function getUrl(): string
    {
        $url = $this->url;
        if (!str_ends_with($url, '/')) {
            $url .= '/';
        }

        return $url;
    }

    public function getAppName(): string
    {
        return $this->appName;
    }

    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function getProxyKey(): ?string
    {
        return $this->proxyKey;
    }

    public function setProxyKey(?string $proxyKey): self
    {
        $this->proxyKey = $proxyKey;

        return $this;
    }

    public function setCache(CacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    public function setStaleCache(?CacheInterface $cache): self
    {
        $this->staleCache = $cache;

        return $this;
    }

    public function setTtl(int $ttl): self
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function getMetricsInterval(): int
    {
        return $this->metricsInterval;
    }

    public function isMetricsEnabled(): bool
    {
        return $this->metricsEnabled;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function setAppName(string $appName): self
    {
        $this->appName = $appName;

        return $this;
    }

    public function setInstanceId(string $instanceId): self
    {
        $this->instanceId = $instanceId;

        return $this;
    }

    public function setMetricsInterval(int $metricsInterval): self
    {
        $this->metricsInterval = $metricsInterval;

        return $this;
    }

    public function setMetricsEnabled(bool $metricsEnabled): self
    {
        $this->metricsEnabled = $metricsEnabled;

        return $this;
    }

    /**
     * @return array<string,string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array<string,string> $headers
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function isAutoRegistrationEnabled(): bool
    {
        return $this->autoRegistrationEnabled;
    }

    public function setAutoRegistrationEnabled(bool $autoRegistrationEnabled): self
    {
        $this->autoRegistrationEnabled = $autoRegistrationEnabled;

        return $this;
    }

    public function getDefaultContext(): Context
    {
        return $this->getContextProvider()->getContext();
    }

    public function getContextProvider(): UnleashContextProvider
    {
        return $this->contextProvider;
    }

    public function setContextProvider(UnleashContextProvider $contextProvider): self
    {
        $this->contextProvider = $contextProvider;

        return $this;
    }

    #[Pure]
    public function getBootstrapHandler(): BootstrapHandler
    {
        return $this->bootstrapHandler;
    }

    public function setBootstrapHandler(BootstrapHandler $bootstrapHandler): self
    {
        $this->bootstrapHandler = $bootstrapHandler;

        return $this;
    }

    #[Pure]
    public function getBootstrapProvider(): BootstrapProvider
    {
        return $this->bootstrapProvider;
    }

    public function setBootstrapProvider(BootstrapProvider $bootstrapProvider): self
    {
        $this->bootstrapProvider = $bootstrapProvider;

        return $this;
    }

    public function isFetchingEnabled(): bool
    {
        return $this->fetchingEnabled;
    }

    public function setFetchingEnabled(bool $fetchingEnabled): self
    {
        $this->fetchingEnabled = $fetchingEnabled;

        return $this;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): self
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    public function getStaleTtl(): int
    {
        return $this->staleTtl;
    }

    public function setStaleTtl(int $staleTtl): self
    {
        $this->staleTtl = $staleTtl;

        return $this;
    }
}
