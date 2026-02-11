<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

class DeckCardMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'deck_cards');
	}

	public function countCards(): int {
		$qb = $this->db->getQueryBuilder();
		$qb->selectAlias($qb->createFunction('COUNT(*)'), 'card_count')
			->from('deck_cards');
		$result = $qb->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();
		return (int)$row['card_count'];
	}
}
