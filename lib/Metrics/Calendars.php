<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

use OCP\IDBConnection;

class Calendars
{
    public function __construct(
        private IDBConnection $db,
    ) {}

    public function countCalendars(): int
    {
        $qb = $this->db->getQueryBuilder();
        $qb->selectAlias($qb->createFunction('COUNT(*)'), 'calendar_count')
            ->from('calendars');
        $result = $qb->executeQuery();
        $row = $result->fetch();
        $result->closeCursor();
        return (int)$row['calendar_count'];
    }

    public function countAddressbooks(): int
    {
        $qb = $this->db->getQueryBuilder();
        $qb->selectAlias($qb->createFunction('COUNT(*)'), 'addressbook_count')
            ->from('addressbooks');
        $result = $qb->executeQuery();
        $row = $result->fetch();
        $result->closeCursor();
        return (int)$row['addressbook_count'];
    }

    public function countContacts(): int
    {
        $qb = $this->db->getQueryBuilder();
        $qb->selectAlias($qb->createFunction('COUNT(*)'), 'contact_count')
            ->from('cards');
        $result = $qb->executeQuery();
        $row = $result->fetch();
        $result->closeCursor();
        return (int)$row['contact_count'];
    }

    public function getMetrics(): array
    {
        return [
            'Number of Calendars' => $this->countCalendars(),
            'Number of Adressbooks' => $this->countAddressbooks(),
            'Number of Contacts' => $this->countContacts()
        ];
    }
}
