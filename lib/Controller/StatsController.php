<?php

namespace OCA\FramaSpace\Controller;

use OCA\FramaSpace\Metrics\Calendars;
use OCA\FramaSpace\Metrics\Chats;
use OCA\FramaSpace\Metrics\Circles;
use OCA\FramaSpace\Metrics\Collectives;
use OCA\FramaSpace\Metrics\Conversations;
use OCA\FramaSpace\Metrics\Deck;
use OCA\FramaSpace\Metrics\Filecache;
use OCA\FramaSpace\Metrics\Forms;
use OCA\FramaSpace\Metrics\Tables;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\CORS;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

/**
 * @psalm-suppress UnusedClass
 */

class StatsController extends OCSController {

	public function __construct(
		string $appName,
		IRequest $request,
		private Deck $deck,
		private Tables $tables,
		private Forms $forms,
		private Collectives $collectives,
		private Circles $circles,
		private Calendars $calendars,
		private Conversations $conversations,
		private Chats $chats,
		private Filecache $filecache,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * Get statistics for all apps
	 *
	 * @return DataResponse<Http::STATUS_OK, array{deck: array, tables: array, forms: array, collectives: array, circles: array, calendars: array, conversations: array, chats: array, filecache: array}, array{}>
	 */
	#[NoAdminRequired]
	#[NoCSRFRequired]
	#[CORS]
	#[ApiRoute(verb: 'GET', url: '/api/v1/stats')]
	public function getStats() {
		return new DataResponse([
			'deck' => $this->deck->getMetrics(),
			'tables' => $this->tables->getMetrics(),
			'forms' => $this->forms->getMetrics(),
			'collectives' => $this->collectives->getMetrics(),
			'circles' => $this->circles->getMetrics(),
			'calendars' => $this->calendars->getMetrics(),
			'conversations' => $this->conversations->getMetrics(),
			'chats' => $this->chats->getMetrics(),
			'filecache' => $this->filecache->getMetrics()
		]);
	}
}
