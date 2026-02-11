<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;

class Deck {
	public function __construct(
		private IDBConnection $db,
	) {
	}

	public function countCards(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'card_count')
			->from('deck_cards');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['card_count'];
	}

	/*
	public function countBoards(): int
	{
	}
	*/

	public function getMetrics(): array {
		return [
			'cards' => $this->countCards(),
			//'boards' => $this->countBoards()
		];
	}
}
