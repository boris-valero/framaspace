<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
class Tables extends BaseMetrics {
	public function countTables(): int {
		return $this->executeCount('tables_tables', 'table_count');
	}

	public function countRows(): int {
		return $this->executeCount('tables_rows', 'row_count');
	}

	public function getMetrics(): array {
		return [
			'tables' => $this->countTables(),
			'rows' => $this->countRows()
		];
	}
}
