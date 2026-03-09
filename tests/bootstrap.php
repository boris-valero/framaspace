<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$bootstrapFile = __DIR__ . '/../../../tests/bootstrap.php';
if (file_exists($bootstrapFile)) {
	require_once $bootstrapFile;
	\OC_App::loadApp(OCA\FramaSpace\AppInfo\Application::APP_ID);
	OC_Hook::clear();
}
