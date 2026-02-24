<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\DB\QueryBuilder\IQueryBuilder;
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

	/**
	 * @return array<int, array{username: string, size_bytes: int}>
	 */
	public function getTop5StorageUsers(): array {
		$qb = $this->db->getQueryBuilder();
		$qb->select('s.id')
			->selectAlias(
				$qb->func()->sum('f.size'),
				'total_size'
			)
			->from('filecache', 'f')
			->innerJoin('f', 'storages', 's', $qb->expr()->eq('f.storage', 's.numeric_id'))
			->where($qb->expr()->like('s.id', $qb->createNamedParameter('home::%', IQueryBuilder::PARAM_STR)))
			->andWhere($qb->expr()->neq('f.mimetype', $qb->createNamedParameter(2, IQueryBuilder::PARAM_INT)))
			->andWhere($qb->expr()->like('f.path', $qb->createNamedParameter('files/%', IQueryBuilder::PARAM_STR)))
			->groupBy('s.id')
			->orderBy('total_size', 'DESC')
			->setMaxResults(5);
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
	 * @return array<int, array{filename: string, size_bytes: int, path: string, owner: string}>
	 */
	public function getTop10BiggestFiles(): array {
		$qb = $this->db->getQueryBuilder();
		$qb->select('f.name', 'f.size', 'f.path', 's.id')
			->from('filecache', 'f')
			->innerJoin('f', 'storages', 's', $qb->expr()->eq('f.storage', 's.numeric_id'))
			->where($qb->expr()->neq('f.mimetype', $qb->createNamedParameter(2, IQueryBuilder::PARAM_INT)))
			->andWhere($qb->expr()->like('s.id', $qb->createNamedParameter('home::%', IQueryBuilder::PARAM_STR)))
			->andWhere($qb->expr()->like('f.path', $qb->createNamedParameter('files/%', IQueryBuilder::PARAM_STR)))
			->orderBy('f.size', 'DESC')
			->setMaxResults(10);
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
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->func()->sum('size'), 'total_size')
			->from('filecache')
			->where($qb->expr()->neq('mimetype', $qb->createNamedParameter(2, IQueryBuilder::PARAM_INT)))
			->andWhere($qb->expr()->like('path', $qb->createNamedParameter('files_versions/%', IQueryBuilder::PARAM_STR)));
		$result = $qb->executeQuery();
		$totalSize = (int)($result->fetchOne() ?? 0);
		$result->closeCursor();
		return $totalSize;
	}

	public function getMetrics(): array {
		return [
			'storage' => $this->getTotalStorageSize(),
			'files' => $this->countFiles(),
			'top5users' => $this->getTop5StorageUsers(),
			'top10files' => $this->getTop10BiggestFiles(),
			'version_storage' => $this->getTotalVersionsStorage(),
		];
	}
}
