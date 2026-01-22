<?php

namespace OCA\FramaSpace\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\IRequest;
use OCP\App\IAppManager;
use OCA\FramaSpace\Service\ConfigProxy;

class AdminApiController extends Controller {
    private IAppManager $appManager;
    private ConfigProxy $config;

    public function __construct($AppName, IRequest $request, IAppManager $appManager, ConfigProxy $config) {
        parent::__construct($AppName, $request);
        $this->appManager = $appManager;
        $this->config = $config;
    }

    #[NoCSRFRequired]
    public function getApps() {
        $navigationEntries = \OC::$server->getNavigationManager()->getAll();
        $hiddenApps = $this->config->getAppValueArray('hidden_apps');
        $protectedApps = ['files', 'activity'];
        $appsData = [];
        foreach ($navigationEntries as $entry) {
            if (!is_array($entry) || !isset($entry['id']) || !is_string($entry['id'])) continue;
            $appId = $entry['id'];
            if ($this->appManager->isInstalled($appId)) {
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
    public function setHidden() {
        $params = $this->request->getParams();
        $hidden = isset($params['hidden']) ? $params['hidden'] : [];
        $this->config->setAppValueArray('hidden_apps', $hidden);
        return new DataResponse(['success' => true]);
    }
}