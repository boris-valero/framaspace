<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
abstract class BaseMetrics {
	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct(
		protected IDBConnection $db,
	) {
	}

	/**
	 * Execute a COUNT query on a table
	 *
	 * @param string $table The table name to count from
	 * @param string $countAlias The alias for the COUNT result
	 * @param (callable(IQueryBuilder): void)|null $addWhere Optional callback to add WHERE conditions
	 * @return int The count result
	 */
	protected function executeCount(
		string $table,
		string $countAlias,
		?callable $addWhere = null,
	): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), $countAlias)
			->from($table);

		if ($addWhere !== null) {
			$addWhere($qb);
		}

		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)($row[$countAlias] ?? 0);
	}

	/**
	 * Extract username from storage identifier
	 *
	 * @param string $storageId The storage identifier (e.g., 'home::user' or 'object::user:user')
	 * @return string The extracted username
	 */
	public static function extractUsername(string $storageId): string {
		return preg_replace('/^(?:home|object::user)::/', '', $storageId) ?? '';
	}

	/**
	 * Join filecache with storages table
	 *
	 * @param IQueryBuilder $qb The query builder
	 * @return void
	 */
	protected function joinStorages(IQueryBuilder $qb): void {
		$qb->innerJoin('f', 'storages', 's', $qb->expr()->eq('f.storage', 's.numeric_id'));
	}

	/**
	 * Join filecache with mimetypes table
	 *
	 * @param IQueryBuilder $qb The query builder
	 * @return void
	 */
	protected function joinMimetypes(IQueryBuilder $qb): void {
		$qb->innerJoin('f', 'mimetypes', 'm', $qb->expr()->eq('f.mimetype', 'm.id'));
	}

	/**
	 * Get all metrics for this feature
	 *
	 * @return array The metrics array
	 */
	abstract public function getMetrics(): array;
}
