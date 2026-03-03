<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCA\FramaSpace\Config\MetricsConfig;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
class Filecache {
	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	private IDBConnection $connection;

	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct(IDBConnection $connection) {
		$this->connection = $connection;
	}

	public function getTotalStorageSize(): int {
		$qb = $this->connection->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('SUM(f.size)'), 'total_size')
			->from('filecache', 'f')
			->innerJoin('f', 'storages', 's', $qb->expr()->eq('f.storage', 's.numeric_id'))
			->where($qb->expr()->eq('f.path', $qb->createNamedParameter('')))
			->andWhere(
				$qb->expr()->orX(
					$qb->expr()->like('s.id', $qb->createNamedParameter(MetricsConfig::STORAGE_HOME_PATTERN, IQueryBuilder::PARAM_STR)),
					$qb->expr()->like('s.id', $qb->createNamedParameter(MetricsConfig::STORAGE_OBJECT_USER_PATTERN, IQueryBuilder::PARAM_STR)),
					$qb->expr()->like('s.id', $qb->createNamedParameter(MetricsConfig::STORAGE_LOCAL_PATTERN, IQueryBuilder::PARAM_STR)),
					$qb->expr()->like('s.id', $qb->createNamedParameter(MetricsConfig::STORAGE_OBJECT_AMAZON_PATTERN, IQueryBuilder::PARAM_STR))
				)
			);
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)($row['total_size'] ?? 0);
	}

	public function countFiles(): int {
		$qb = $this->connection->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'file_count')
			->from('filecache')
			->where($qb->expr()->neq('mimetype', $qb->createNamedParameter(MetricsConfig::MIMETYPE_FOLDER, IQueryBuilder::PARAM_INT)));
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)($row['file_count'] ?? 0);
	}

	/**
	 * @return list<array{username: string, size_bytes: int}>
	 */
	public function getTopStorageUsers(): array {
		$qb = $this->connection->getQueryBuilder();

		$qb->select('s.id')
			->selectAlias(
				$qb->func()->sum('f.size'),
				'total_size'
			)
			->from('filecache', 'f')
			->innerJoin('f', 'storages', 's', $qb->expr()->eq('f.storage', 's.numeric_id'))
			->where($qb->expr()->like('s.id', $qb->createNamedParameter(MetricsConfig::STORAGE_HOME_PATTERN, IQueryBuilder::PARAM_STR)))
			->andWhere($qb->expr()->neq('f.mimetype', $qb->createNamedParameter(MetricsConfig::MIMETYPE_FOLDER, IQueryBuilder::PARAM_INT)))
			->andWhere($qb->expr()->like('f.path', $qb->createNamedParameter(MetricsConfig::STORAGE_FILES_PATH_PATTERN, IQueryBuilder::PARAM_STR)))
			->groupBy('s.id')
			->orderBy('total_size', 'DESC')
			->setMaxResults(MetricsConfig::N_TOP_STORAGE_USERS);

		$result = $qb->executeQuery();
		$rows = $result->fetchAll();
		$result->closeCursor();
		$users = [];
		foreach ($rows as $row) {
			$users[] = [
				'username' => preg_replace('/^home::/', '', (string)$row['id']) ?? '',
				'size_bytes' => (int)($row['total_size'] ?? 0),
			];
		}
		return $users;
	}

	/**
	 * @return list<array{filename: string, size_bytes: int, path: string, owner: string}>
	 */
	public function getTopBiggestFiles(): array {
		$qb = $this->connection->getQueryBuilder();
		$qb->select('f.name', 'f.size', 'f.path', 's.id')
			->from('filecache', 'f')
			->innerJoin('f', 'storages', 's', $qb->expr()->eq('f.storage', 's.numeric_id'))
			->where($qb->expr()->neq('f.mimetype', $qb->createNamedParameter(MetricsConfig::MIMETYPE_FOLDER, IQueryBuilder::PARAM_INT)))
			->andWhere($qb->expr()->like('s.id', $qb->createNamedParameter(MetricsConfig::STORAGE_HOME_PATTERN, IQueryBuilder::PARAM_STR)))
			->andWhere($qb->expr()->like('f.path', $qb->createNamedParameter(MetricsConfig::STORAGE_FILES_PATH_PATTERN, IQueryBuilder::PARAM_STR)))
			->orderBy('f.size', 'DESC')
			->setMaxResults(MetricsConfig::N_TOP_BIGGEST_FILES);

		$result = $qb->executeQuery();
		$rows = $result->fetchAll();
		$result->closeCursor();
		$files = [];
		foreach ($rows as $row) {
			$files[] = [
				'filename' => (string)($row['name'] ?? ''),
				'size_bytes' => (int)($row['size'] ?? 0),
				'path' => (string)($row['path'] ?? ''),
				'owner' => preg_replace('/^home::/', '', (string)$row['id']) ?? '',
			];
		}
		return $files;
	}

	public function getTotalVersionsStorage(): int {
		$qb = $this->connection->getQueryBuilder();
		$qb->selectAlias($qb->func()->sum('size'), 'total_size')
			->from('filecache')
			->where($qb->expr()->neq('mimetype', $qb->createNamedParameter(MetricsConfig::MIMETYPE_FOLDER, IQueryBuilder::PARAM_INT)))
			->andWhere($qb->expr()->like('path', $qb->createNamedParameter(MetricsConfig::STORAGE_VERSIONS_PATH_PATTERN, IQueryBuilder::PARAM_STR)));
		$result = $qb->executeQuery();
		$totalSize = (int)($result->fetchOne() ?? 0);
		$result->closeCursor();
		return $totalSize;
	}

	/**
	 * @return list<array{username: string, files_count: int, trash_bytes: int}>
	 */
	public function getTopTrashByUser(): array {
		$qb = $this->connection->getQueryBuilder();
		$qb->select('s.id')
			->selectAlias($qb->func()->count('f.fileid'), 'files_count')
			->selectAlias($qb->func()->sum('f.size'), 'total_size')
			->from('filecache', 'f')
			->innerJoin('f', 'storages', 's', $qb->expr()->eq('f.storage', 's.numeric_id'))
			->where($qb->expr()->like('s.id', $qb->createNamedParameter(MetricsConfig::STORAGE_HOME_PATTERN, IQueryBuilder::PARAM_STR)))
			->andWhere($qb->expr()->neq('f.mimetype', $qb->createNamedParameter(MetricsConfig::MIMETYPE_FOLDER, IQueryBuilder::PARAM_INT)))
			->andWhere($qb->expr()->like('f.path', $qb->createNamedParameter(MetricsConfig::STORAGE_TRASHBIN_PATH_PATTERN, IQueryBuilder::PARAM_STR)))
			->groupBy('s.id')
			->orderBy('total_size', 'DESC')
			->setMaxResults(MetricsConfig::N_TOP_TRASH_USERS);

		$result = $qb->executeQuery();
		$rows = $result->fetchAll();
		$result->closeCursor();
		$users = [];
		foreach ($rows as $row) {
			$users[] = [
				'username' => preg_replace('/^home::/', '', (string)$row['id']) ?? '',
				'files_count' => (int)($row['files_count'] ?? 0),
				'trash_bytes' => (int)($row['total_size'] ?? 0),
			];
		}
		return $users;
	}

	public function getMetrics(): array {
		return [
			'storage' => $this->getTotalStorageSize(),
			'files' => $this->countFiles(),
			'top5users' => $this->getTopStorageUsers(),
			'top10biggestfiles' => $this->getTopBiggestFiles(),
			'version_storage' => $this->getTotalVersionsStorage(),
			'top3biggesttrash' => $this->getTopTrashByUser(),
		];
	}
}
