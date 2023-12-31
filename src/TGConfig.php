<?php

namespace Ctlynl\Tgpic;

use Ctlynl\Tgpic\Exception\TGConfigException;

/**
 * @property mixed|string $baseUrl
 * @property mixed|array $globalHeaders
 * @property mixed|string $userStoragePath
 * @property mixed|string $userName
 * @property mixed|string $password
 * @\Ctlynl\Tgpic\TGConfig
 */
class TGConfig
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @throws TGConfigException
     */
    public function __get($name)
    {
        if (!isset($this->config[$name])) {
            throw new TGConfigException("config parameter does not exist[$name]");
        }
        return $this->config[$name];
    }

    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * @return string
     */
    public function getUserStoragePath(): string
    {
        return rtrim($this->userStoragePath, DIRECTORY_SEPARATOR);
    }
}
