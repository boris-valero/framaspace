<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
class Circles {
	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct(
		private IDBConnection $db,
	) {
	}

	public function circlesCount(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'circle_count')
			->from('circles_circle');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['circle_count'];
	}

	public function getMetrics(): array {
		return [
			'circles' => $this->circlesCount()
		];
	}
}
