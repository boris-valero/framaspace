<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\OpenAPI;
use OCP\AppFramework\Http\RedirectResponse;

class PageController extends Controller {
	#[NoCSRFRequired]
	#[NoAdminRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'GET', url: '/')]
	public function index(): RedirectResponse {
		return new RedirectResponse('https://forum.frama.space/t/centre-de-ressources/71');
	}

	#[NoCSRFRequired]
	#[NoAdminRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'GET', url: '/admin')]
	public function admin(): RedirectResponse {
		return new RedirectResponse('https://forum.frama.space/t/premiers-pas-pour-les-personnes-administratrices-dun-espace-frama-space/74');
	}
}
