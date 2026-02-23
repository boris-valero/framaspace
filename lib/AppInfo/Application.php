<?php

declare(strict_types=1);

namespace OCA\FramaSpace\AppInfo;

use OCA\FramaSpace\Listener\CSSInjectionListener;
use OCA\FramaSpace\Service\ConfigProxy; // <-- ton service config custom
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;

class Application extends App implements IBootstrap {
	public const APP_ID = 'framaspace';

	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(BeforeTemplateRenderedEvent::class, CSSInjectionListener::class);

		// Enregistrement explicite du service ConfigProxy si nécessaire
		$context->registerService(ConfigProxy::class, function ($c) {
			// Injection du service de config natif de Nextcloud si besoin
			/** @var \OCP\IAppConfig $appConfig */
			$appConfig = $c->get(\OCP\IAppConfig::class);
			return new ConfigProxy($appConfig);
		});
	}

	public function boot(IBootContext $context): void {
		// Optionnel: place tes hooks, etc ici !
	}
}
