<?php

namespace OCA\FramaSpace\Service;

use OCA\FramaSpace\AppInfo\Application;
use OCP\IConfig;

/**
 * Service de proxy pour la configuration de FramaSpace
 */
class ConfigProxy
{
    public function __construct(protected IConfig $config)
    {
    }

    public function getAppValue(string $name, string $default, string $appId = null): string
    {
        return (string) $this->config->getAppValue($appId ?? Application::APP_ID, $name, $default);
    }

    public function setAppValue(string $name, string $value, string $appId = null): void
    {
        $this->config->setAppValue($appId ?? Application::APP_ID, $name, $value);
    }

    public function getAppValueArray(string $name, string $default, string $appId = null): array
    {
        return (array) json_decode($this->getAppValue($name, $default, $appId), true);
    }

    public function setAppValueArray(string $name, array $value, string $appId = null): void
    {
        $this->setAppValue($name, json_encode($value), $appId);
    }
}