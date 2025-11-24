<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Service\ConfigProxy;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

/**
 * Contrôleur pour les paramètres d'administration
 *
 * @psalm-suppress UnusedClass
 */
class AdminController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private ConfigProxy $config,
	) {
		parent::__construct($appName, $request);
	}

	#[NoCSRFRequired]
	#[FrontpageRoute(verb: 'POST', url: '/admin/hidden-apps')]
	public function setHiddenApps(): JSONResponse {
		$hiddenAppsParam = $this->request->getParam('hidden_apps', '[]');

		// Si c'est déjà un tableau, on le garde tel quel
		if (is_array($hiddenAppsParam)) {
			/** @var array<int, mixed> $hiddenApps */
			$hiddenApps = $hiddenAppsParam;
		} else {
			// Sinon, on décode le JSON
			if (!is_string($hiddenAppsParam)) {
				return new JSONResponse(['error' => 'Invalid parameter type'], 400);
			}
			$decoded = json_decode($hiddenAppsParam, true);
			if ($decoded === null || !is_array($decoded)) {
				return new JSONResponse(['error' => 'Invalid JSON format'], 400);
			}
			$hiddenApps = $decoded;
		}

		// Validation : s'assurer que tous les éléments sont des chaînes
		/** @var array<string> $validatedApps */
		$validatedApps = [];
		foreach ($hiddenApps as $appId) {
			if (!is_string($appId)) {
				return new JSONResponse(['error' => 'Invalid app ID format'], 400);
			}
			$validatedApps[] = $appId;
		}

		// Sauvegarde dans la configuration
		$this->config->setAppValueArray('hidden_apps', $validatedApps);

		return new JSONResponse(['success' => true, 'hidden_apps' => $validatedApps]);
	}
}
