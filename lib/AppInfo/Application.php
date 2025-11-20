<?php

declare(strict_types=1);

namespace OCA\FramaSpace\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Util;

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
		// Injection du CSS pour masquer les applications sur toutes les pages
		$this->injectHiddenAppsCSS();
	}

	/**
	 * Injection du CSS pour masquer les applications
	 */
	private function injectHiddenAppsCSS(): void {
		// Ajouter le CSS dynamique pour masquer les applications
		$url = \OC::$server->getURLGenerator()->linkToRoute('framaspace.css.hiddenApps');
		Util::addHeader('link', [
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'href' => $url
		]);
	}
}
