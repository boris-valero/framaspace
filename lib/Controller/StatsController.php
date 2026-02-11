<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Db\DeckCardMapper;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

class StatsController extends OCSController {
	private DeckCardMapper $deckCardMapper;

	public function __construct(
		string $appName,
		IRequest $request,
		DeckCardMapper $deckCardMapper,
	) {
		parent::__construct($appName, $request);
		$this->deckCardMapper = $deckCardMapper;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @CORS
	 */
	public function getStats(): DataResponse {
		return new DataResponse([
			'deck' => [
				'cards' => $this->deckCardMapper->countCards()
			]
		]);
	}
}
