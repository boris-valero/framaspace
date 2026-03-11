<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Listener;

use OCA\FramaSpace\AppInfo\Application;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\Util;
use Psr\Log\LoggerInterface;

/**
 * @implements IEventListener<BeforeTemplateRenderedEvent>
 */
class CSSInjectionListener implements IEventListener {

	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct(
		private IConfig $config,
		private LoggerInterface $logger,
	) {
	}

	public function handle(Event $event): void {
		if (!($event instanceof BeforeTemplateRenderedEvent)) {
			return;
		}

		/** @psalm-suppress DeprecatedMethod */
		$hiddenAppsJson = $this->config->getAppValue(Application::APP_ID, 'hidden_apps', '[]');
		try {
			$decoded = json_decode($hiddenAppsJson, true, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {
			$this->logger->error('Failed to decode hidden apps configuration', ['exception' => $e]);
			return;
		}

		if (!is_array($decoded)) {
			$this->logger->warning('Hidden apps configuration is not an array');
			return;
		}
		$hiddenApps = array_filter($decoded, fn ($appId) => is_string($appId) && !empty($appId));

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

		/** @var string $appId */
		foreach ($hiddenApps as $appId) {
			$css .= ".app-menu-entry:has(a.app-menu-entry__link[href\$=\"/apps/{$appId}/\"]) { display: none; }";
		}

		return $css;
	}
}
