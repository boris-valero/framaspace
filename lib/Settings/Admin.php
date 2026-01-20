<?php

namespace OCA\FramaSpace\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

/**
 * Fournit le panneau d'administration FramaSpace (intégration Vue 3)
 */
class Admin implements ISettings {

    public function getForm(): TemplateResponse {
        return new TemplateResponse('framaspace', 'settings/admin-form', []);
    }

    public function getSection(): string {
        return 'framaspace';
    }

    public function getPriority(): int {
        return 0;
    }
}
