<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;
use OCP\DB\QueryBuilder\IQueryBuilder;

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

	public function countFiles(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'file_count')
			->from('filecache')
			->where($qb->expr()->neq('mimetype', $qb->createNamedParameter(2, IQueryBuilder::PARAM_INT)));
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)($row['file_count'] ?? 0);
	}

	public function getMetrics(): array {
		return [
			'storage' => $this->getTotalStorageSize(),
			'files' => $this->countFiles(),
		];
	}
}
