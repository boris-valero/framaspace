<?php

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Metrics\Deck;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

class StatsController extends OCSController {

	private Deck $deck;

	public function __construct(
		string $appName,
		IRequest $request,
		Deck $deck,
	) {
		parent::__construct($appName, $request);
		$this->deck = $deck;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @CORS
	 */
	public function getStats() {
		return new DataResponse([
			'deck' => $this->deck->getMetrics()
		]);
	}
}
