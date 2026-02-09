<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Listener;

use OCA\FramaSpace\AppInfo\Application;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\Util;

class CSSInjectionListener implements IEventListener {

	private IConfig $config;

	public function __construct(IConfig $config) {
		$this->config = $config;
	}

	public function handle(Event $event): void {
		if (!($event instanceof BeforeTemplateRenderedEvent)) {
			return;
		}

		$hiddenAppsJson = $this->config->getAppValue(Application::APP_ID, 'hidden_apps', '[]');
		$decoded = json_decode($hiddenAppsJson, true);

		if ($decoded === null || !is_array($decoded)) {
			return;
		}
		$hiddenApps = array_filter($decoded, 'is_string');

		if (empty($hiddenApps)) {
			return;
		}

		$this->injectHiddenAppsCSS($hiddenApps);
	}

	private function injectHiddenAppsCSS(array $hiddenApps): void {
		$css = $this->generateHiddenAppsCSS($hiddenApps);
		Util::addHeader('style', ['id' => 'framaspace-hidden-apps'], $css);
	}

	private function generateHiddenAppsCSS(array $hiddenApps): string {
		$css = '';

		foreach ($hiddenApps as $appId) {
			if (!is_string($appId) || empty($appId)) {
				continue;
			}

			$css .= ".app-menu-entry:has(a.app-menu-entry__link[href\$=\"/apps/{$appId}/\"]) { display: none; }";
		}

		return $css;
	}
}
