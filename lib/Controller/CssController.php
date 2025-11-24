<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Service\ConfigProxy;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\PublicPage;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;

/**
 * Contrôleur CSS pour le masquage des applications
 *
 * @psalm-suppress UnusedClass
 */
class CssController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private ConfigProxy $config,
	) {
		parent::__construct($appName, $request);
	}

	#[NoCSRFRequired]
	#[NoAdminRequired]
	#[PublicPage]
	#[FrontpageRoute(verb: 'GET', url: '/css/hidden-apps')]
	public function hiddenApps(): TemplateResponse {
		$hiddenApps = $this->config->getAppValueArray('hidden_apps', '[]');

		$response = new TemplateResponse('framaspace', 'css/hidden-apps', [
			'hidden_apps' => $hiddenApps,
		], 'blank');

		$response->addHeader('Content-Type', 'text/css');

		return $response;
	}
}
