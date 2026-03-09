<?php

namespace OCA\FramaSpace\Service;

use OCA\FramaSpace\AppInfo\Application;
use OCP\IAppConfig;

class ConfigProxy {
	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct(
		protected IAppConfig $config,
	) {
	}

	public function getAppValue(string $name, string $default, ?string $appId = null): string {
		return $this->config->getValueString($appId ?? Application::APP_ID, $name, $default);
	}

	public function setAppValue(string $name, string $value, ?string $appId = null): void {
		$this->config->setValueString($appId ?? Application::APP_ID, $name, $value);
	}

	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function getAppValueArray(string $name, string $default = '[]', ?string $appId = null): array {
		return (array)json_decode($this->getAppValue($name, $default, $appId), true);
	}

	public function setAppValueArray(string $name, array $value, ?string $appId = null): void {
		$this->setAppValue($name, json_encode($value), $appId);
	}
}
