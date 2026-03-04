<?php

declare(strict_types=1);

namespace OCA\FramaSpace\Metrics;

/**
 * @psalm-suppress PossiblyUnusedMethod, MixedAssignment, MixedArrayAccess
 */
class Collectives extends BaseMetrics {
	public function collectivesNumber(): int {
		return $this->executeCount('collectives', 'collectives_number');
	}

	public function collectivesPages(): int {
		return $this->executeCount('collectives_pages', 'page_count');
	}

	public function getMetrics(): array {
		return [
			'collectives' => $this->collectivesNumber(),
			'pages' => $this->collectivesPages()
		];
	}
}
