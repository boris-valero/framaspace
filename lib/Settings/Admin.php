<?php

namespace OCA\FramaSpace\Settings;

use OCP\App\IAppManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\INavigationManager;
use OCP\Settings\ISettings;

/**
 * @psalm-suppress UnusedClass
 */
class Admin implements ISettings {
	public function __construct(
		private IAppManager $appManager,
		private INavigationManager $navigationManager,
	) {
	}

	public function getForm(): TemplateResponse {
		$navigationEntries = $this->navigationManager->getAll();

		$appsData = [];
		foreach ($navigationEntries as $entry) {
			// Vérification que $entry est bien un array avec les clés attendues
			if (!is_array($entry) || !isset($entry['id']) || !is_string($entry['id'])) {
				continue;
			}

			$appId = $entry['id'];

			if ($this->appManager->isInstalled($appId)) {
				$appInfo = $this->appManager->getAppInfo($appId);
				$appsData[] = [
                	'name' => (string)($entry['name'] ?? ($this->appManager->getAppInfo($appId)['name'] ?? $appId)),
                    'enabled' => $this->appManager->isEnabledForUser($appId),
                    'order' => (int)($entry['order'] ?? 0),
				];
			}
		}

		usort($appsData, function (array $a, array $b): int {
			return $a['order'] <=> $b['order'];
		});

		return new TemplateResponse('framaspace', 'settings/admin-form', [
			'apps' => $appsData,
		]);
	}

	public function getSection(): string {
		return 'framaspace';
	}

	public function getPriority(): int {
		return 0;
	}
}
