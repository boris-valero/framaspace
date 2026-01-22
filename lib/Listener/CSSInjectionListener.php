<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Listener;

use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\Util;

/**
 * Listener pour injecter le CSS de masquage sur toutes les pages
 *
 * @implements IEventListener<BeforeTemplateRenderedEvent>
 */
class CSSInjectionListener implements IEventListener {

	private IConfig $config;

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct(IConfig $config) {
		$this->config = $config;
	}

	public function handle(Event $event): void {
		if (!($event instanceof BeforeTemplateRenderedEvent)) {
			return;
		}

		/** @psalm-suppress DeprecatedMethod */
		$hiddenAppsJson = $this->config->getAppValue('framaspace', 'hidden_apps', '[]');
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

	/**
	 * Injection du CSS pour masquer les applications
	 */
	private function injectHiddenAppsCSS(array $hiddenApps): void {
		$css = $this->generateHiddenAppsCSS($hiddenApps);

		// Inject inline CSS with a stable id to avoid malformed headers and duplicates
		Util::addHeader('style', ['id' => 'framaspace-hidden-apps'], $css);

	}

	/**
	 * Génération du CSS pour masquer les applications
	 */
	private function generateHiddenAppsCSS(array $hiddenApps): string {
		$stringApps = array_filter($hiddenApps, 'is_string');
		$css = '/* FramaSpace - Applications masquées: ' . implode(', ', $stringApps) . " */\n";

		$appPositions = [
			'dashboard' => 1,
			'talk' => 2,
			'spreed' => 2,
			'files' => 3,
			'photos' => 4,
			'activity' => 5,
			'mail' => 6,
			'contacts' => 7,
			'calendar' => 8,
			'notes' => 9
		];

		foreach ($hiddenApps as $appId) {
			if (!is_string($appId) || empty($appId)) {
				continue;
			}

			$position = $appPositions[$appId] ?? null;

			if ($position !== null) {
				$css .= "
/* Masquer {$appId} à la position {$position} */
li.app-menu-entry:nth-child({$position}) {
    display: none;
}";
			}

			$css .= "
/* Masquer {$appId} par sélecteurs génériques */
#appmenu li[data-id=\"{$appId}\"],
.header-appsmenu li[data-id=\"{$appId}\"],
.apps-menu .app-entry[data-app=\"{$appId}\"],
.app-grid .app-entry[data-app=\"{$appId}\"],
[data-app-id=\"{$appId}\"],
[data-app=\"{$appId}\"],
a[href*=\"/apps/{$appId}/\"],
a[href*=\"index.php/apps/{$appId}\"] {
    display: none;
}";
		}

		$css .= '
/* Réorganiser le menu pour éliminer les trous */
.app-menu {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-items: center;
    justify-content: flex-start;
    gap: 0;
}

.app-menu li.app-menu-entry {
    flex: 0 0 auto;
    position: relative;
}';

		return $css;
	}
}
