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

	public function getTotalStorageSizeInBytes(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('SUM(size)'), 'total_size_bytes')
			->from('filecache')
			->where($qb->expr()->gt('storage', $qb->createNamedParameter(0)));
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)($row['total_size_bytes'] ?? 0);
	}

	public function getTotalStorageSizeInGB(): float {
		return round($this->getTotalStorageSizeInBytes() / (1024 ** 3), 2);
	}

	public function getMetrics(): array {
		return [
			'storage' => $this->getTotalStorageSizeInGB(),
		];
	}
}
