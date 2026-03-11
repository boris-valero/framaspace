<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Settings;

use OCA\FramaSpace\AppInfo\Application;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

/**
 * Provides the FramaSpace admin panel
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
