<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
class Filecache {
	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct(
		private IDBConnection $db,
	) {
	}

	public function getTotalStorageSize(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('SUM(size)'), 'total_size')
			->from('filecache')
			->where($qb->expr()->gt('storage', $qb->createNamedParameter(0)));
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)($row['total_size'] ?? 0);
	}

	public function getMetrics(): array {
		return [
			'storage' => $this->getTotalStorageSize(),
		];
	}
}
