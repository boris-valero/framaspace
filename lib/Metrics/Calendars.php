<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
class Calendars {
	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct(
		private IDBConnection $db,
	) {
	}

	public function countCalendars(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'calendar_count')
			->from('calendars');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['calendar_count'];
	}

	public function countAddressbooks(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'addressbook_count')
			->from('addressbooks');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['addressbook_count'];
	}

	public function countContacts(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'contact_count')
			->from('cards');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['contact_count'];
	}

	public function countEvents(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'event_count')
			->from('calendarobjects')
			->where($qb->expr()->eq('componenttype', $qb->createNamedParameter('VEVENT')));
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['event_count'];
	}

	public function countTasks(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'task_count')
			->from('calendarobjects')
			->where($qb->expr()->eq('componenttype', $qb->createNamedParameter('VTODO')));
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['task_count'];
	}

	public function getMetrics(): array {
		return [
			'calendars' => $this->countCalendars(),
			'addressbooks' => $this->countAddressbooks(),
			'contacts' => $this->countContacts(),
			'events' => $this->countEvents(),
			'tasks' => $this->countTasks()
		];
	}
}
