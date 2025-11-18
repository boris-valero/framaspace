<?php

declare(strict_types=1);

namespace OCA\FramaSpace\AppInfo;

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
		// Les paramètres d'administration sont déclarés dans appinfo/info.xml
		// Pas besoin de les enregistrer ici
	}

	public function boot(IBootContext $context): void {
		$context->injectFn($this->injectCss(...));
	}

	/**
	 * Injecte le CSS pour masquer les applications sélectionnées
	 */
	public function injectCss(): void {
		$appManager = \OC::$server->getAppManager();
		
		// Vérifier si l'app est activée
		if ($appManager->isEnabledForUser(self::APP_ID)) {
			\OCP\Util::addHeader('link', [
				'rel' => 'stylesheet',
				'type' => 'text/css',
				'href' => \OC::$server->getURLGenerator()->linkToRoute('framaspace.css.hiddenApps'),
			]);
		}
	}
}
