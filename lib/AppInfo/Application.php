<?php

declare(strict_types=1);

namespace OCA\FramaSpace\AppInfo;

use OCA\FramaSpace\Settings\Admin;
use OCA\FramaSpace\Settings\AdminSection;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

/**
 * @psalm-suppress UnusedClass
 */
class Application extends App implements IBootstrap {
	public const APP_ID = 'framaspace';

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerSettingsSection(AdminSection::class);
		$context->registerSettings(Admin::class);
	}

	public function boot(IBootContext $context): void {
	}
}
