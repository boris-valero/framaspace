<?php

namespace OCA\FramaSpace\Settings;

use OCP\App\IAppManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

/**
 * @psalm-suppress UnusedClass
 */
class Admin implements ISettings {
	public function __construct(
		private IAppManager $appManager,
	) {
	}

	public function getForm(): TemplateResponse {
		// Récupérer la liste des applications installées
		$installedApps = $this->appManager->getInstalledApps();
		
		// Préparer les données pour le template
		$appsData = [];
		foreach ($installedApps as $appId) {
			$appInfo = $this->appManager->getAppInfo($appId);
			$appsData[] = [
				'id' => $appId,
				'name' => $appInfo['name'] ?? $appId,
				'version' => $appInfo['version'] ?? 'N/A',
				'enabled' => $this->appManager->isEnabledForUser($appId),
			];
		}

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
