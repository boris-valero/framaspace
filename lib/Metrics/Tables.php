<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
class Tables {

	public function __construct(
		private IDBConnection $db,
	) {
	}

	public function countTables(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'table_count')
			->from('tables_tables');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['table_count'];
	}

	public function countRows(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'row_count')
			->from('tables_rows');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['row_count'];
	}

	public function getMetrics(): array {
		return [
			'tables' => $this->countTables(),
			'rows' => $this->countRows()
		];
	}
}
