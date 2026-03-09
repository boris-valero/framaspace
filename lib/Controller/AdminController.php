<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Service\ConfigProxy;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

/**
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

	#[FrontpageRoute(verb: 'POST', url: '/admin/hidden-apps')]
	public function setHiddenApps(): JSONResponse {
		$hiddenAppsParam = $this->request->getParam('hidden_apps', '[]');
		$protectedApps = ['files', 'activity'];

		if (is_array($hiddenAppsParam)) {
			/** @var array<int, mixed> $hiddenApps */
			$hiddenApps = $hiddenAppsParam;
		} else {
			if (!is_string($hiddenAppsParam)) {
				return new JSONResponse(['error' => 'Invalid parameter type'], 400);
			}
			try {
				/** @var array<array-key, mixed> $hiddenApps */
				$hiddenApps = json_decode($hiddenAppsParam, true, 512, JSON_THROW_ON_ERROR);
			} catch (\JsonException) {
				return new JSONResponse(['error' => 'Invalid JSON format'], 400);
			}
		}

		/** @var array<string> $validatedApps */
		$validatedApps = [];
		foreach ($hiddenApps as $appId) {
			if (!is_string($appId)) {
				return new JSONResponse(['error' => 'Invalid app ID format'], 400);
			}
			$validatedApps[] = $appId;
		}

		$filteredApps = array_diff($validatedApps, $protectedApps);
		$ignoredProtected = array_intersect($validatedApps, $protectedApps);

		$this->config->setAppValueArray('hidden_apps', $filteredApps);

		return new JSONResponse([
			'success' => true,
			'hidden_apps' => $filteredApps,
			'ignored_protected_apps' => $ignoredProtected,
		]);
	}
}
