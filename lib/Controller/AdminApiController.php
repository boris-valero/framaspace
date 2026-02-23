<?php

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Service\ConfigProxy;
use OCP\App\IAppManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\INavigationManager;
use OCP\IRequest;
use OCP\IUserSession;

/**
 * @psalm-suppress UnusedClass
 */
class AdminApiController extends Controller {
	private IAppManager $appManager;
	private ConfigProxy $config;
	private IUserSession $userSession;
	private INavigationManager $navigationManager;

	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct(string $AppName, IRequest $request, IAppManager $appManager, ConfigProxy $config, IUserSession $userSession, INavigationManager $navigationManager) {
		parent::__construct($AppName, $request);
		$this->appManager = $appManager;
		$this->config = $config;
		$this->userSession = $userSession;
		$this->navigationManager = $navigationManager;
	}

	#[NoCSRFRequired]
	public function getApps(): DataResponse {
		$navigationEntries = $this->navigationManager->getAll();
		$hiddenApps = $this->config->getAppValueArray('hidden_apps');
		$protectedApps = ['files', 'activity'];
		/** @var array<array<string, mixed>> $appsData */
		$appsData = [];
		$user = $this->userSession->getUser();
		foreach ($navigationEntries as $entry) {
			if (!is_array($entry) || !isset($entry['id']) || !is_string($entry['id'])) {
				continue;
			}
			$appId = $entry['id'];
			if ($user && $this->appManager->isEnabledForUser($appId, $user)) {
				$appsData[] = [
					'id' => $appId,
					'name' => (string)($entry['name'] ?? $appId),
					'hidden' => in_array($appId, $hiddenApps),
					'protected' => in_array($appId, $protectedApps)
				];
			}
		}
		return new DataResponse($appsData);
	}

	#[NoCSRFRequired]
	public function setHidden(): DataResponse {
		$params = $this->request->getParams();
		$hidden = isset($params['hidden']) ? (array)$params['hidden'] : [];
		$this->config->setAppValueArray('hidden_apps', $hidden);
		return new DataResponse(['success' => true]);
	}
}
