<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\OpenAPI;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\IRequest;
use OCP\IURLGenerator;

class PageController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private IURLGenerator $urlGenerator,
	) {
		parent::__construct($appName, $request);
	}

	#[NoCSRFRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'GET', url: '/')]
	public function index(): RedirectResponse {
		return new RedirectResponse(
			$this->urlGenerator->linkToRouteAbsolute('framaspace.page.help')
		);
	}

	#[NoCSRFRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'GET', url: '/help')]
	public function help(): RedirectResponse {
		return new RedirectResponse('https://forum.frama.space/t/centre-de-ressources/71');
	}

	#[NoCSRFRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'GET', url: '/admin')]
	public function admin(): RedirectResponse {
		return new RedirectResponse(
			$this->urlGenerator->linkToRouteAbsolute('framaspace.page.helpAdmin')
		);
	}

	#[NoCSRFRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'GET', url: '/help/admin')]
	public function helpAdmin(): RedirectResponse {
		return new RedirectResponse('https://forum.frama.space/t/premiers-pas-pour-les-personnes-administratrices-dun-espace-frama-space/74');
	}
}
