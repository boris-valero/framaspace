<?php

namespace OCA\FramaSpace\Settings;

use OCA\FramaSpace\AppInfo\Application;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

/**
 * Fournit le panneau d'administration FramaSpace (intégration Vue 3)
 * @psalm-suppress UnusedClass
 */
class Admin implements ISettings {

	public function getForm(): TemplateResponse {
		return new TemplateResponse(Application::APP_ID, 'settings/admin-form', []);
	}

	public function getSection(): string {
		return Application::APP_ID;
	}

	public function getPriority(): int {
		return 0;
	}
}
