<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;

class Collectives {
	public function __construct(
		private IDBConnection $db,
	) {
	}
	public function collectivesNumber(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'collectives_number')
			->from('collectives');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['collectives_number'];
	}

	public function collectivesPages(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'page_count')
			->from('collectives_pages');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['page_count'];
	}

	public function getMetrics(): array {
		return [
			'Number of collectives' => $this->collectivesNumber(),
			'Number of Pages' => $this->collectivesPages()
		];
	}
}
