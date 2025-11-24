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

		// Récupérer les applications masquées depuis la BDD (avec cache)
		/** @psalm-suppress DeprecatedMethod */
		$hiddenAppsJson = $this->config->getAppValue('framaspace', 'hidden_apps', '[]');
		$decoded = json_decode($hiddenAppsJson, true);

		if ($decoded === null || !is_array($decoded)) {
			return;
		}        // Filtrer pour ne garder que les chaînes
		$hiddenApps = array_filter($decoded, 'is_string');

		if (empty($hiddenApps)) {
			return;
		}

		// Générer et injecter le CSS de masquage
		$this->injectHiddenAppsCSS($hiddenApps);
	}	/**
	 * Injection du CSS pour masquer les applications
	 */
	private function injectHiddenAppsCSS(array $hiddenApps): void {
		// Générer le CSS inline pour un masquage immédiat et fiable
		$css = $this->generateHiddenAppsCSS($hiddenApps);

		// Injecter le CSS directement dans le head
		Util::addHeader('style', [], $css);

	}

	/**
	 * Génération du CSS pour masquer les applications
	 */
	private function generateHiddenAppsCSS(array $hiddenApps): string {
		// Filtrer pour s'assurer que tous les éléments sont des chaînes pour implode
		$stringApps = array_filter($hiddenApps, 'is_string');
		$css = '/* FramaSpace - Applications masquées: ' . implode(', ', $stringApps) . " */\n";

		// Positions des applications dans le menu (basées sur l'inspection DOM)
		$appPositions = [
			'dashboard' => 1,
			'talk' => 2,
			'spreed' => 2,    // Talk s'appelle aussi "spreed" en interne
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
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    position: absolute !important;
    left: -9999px !important;
    top: -9999px !important;
    width: 0 !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
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
    display: none !important;
    visibility: hidden !important;
}";
		}

		$css .= '
/* Réorganiser le menu pour éliminer les trous */
.app-menu {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 0 !important;
}

.app-menu li.app-menu-entry {
    flex: 0 0 auto !important;
    position: relative !important;
}

/* Assurer que les éléments visibles restent bien visibles */
li.app-menu-entry:not([style*="display: none"]):not([style*="position: absolute"]) {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    width: auto !important;
    height: auto !important;
}';

		return $css;
	}
}
