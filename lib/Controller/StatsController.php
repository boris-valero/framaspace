<?php

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Metrics\Deck;
use OCA\FramaSpace\Metrics\Tables;
use OCA\FramaSpace\Metrics\Forms;
use OCA\FramaSpace\Metrics\Collectives;
use OCA\FramaSpace\Metrics\Circles;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

class StatsController extends OCSController
{

	public function __construct(
		string $appName,
		IRequest $request,
		private Deck $deck,
		private Tables $tables,
		private Forms $forms,
		private Collectives $collectives,
		private Circles $circles,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @CORS
	 */
	public function getStats()
	{
		return new DataResponse([
			'deck' => $this->deck->getMetrics(),
			'tables' => $this->tables->getMetrics(),
			'forms' => $this->forms->getMetrics(),
			'collectives' => $this->collectives->getMetrics(),
			'circles' => $this->circles->getMetrics()
		]);
	}
}
