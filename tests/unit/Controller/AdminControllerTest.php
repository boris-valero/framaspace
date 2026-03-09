<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SimpleAdminControllerTest extends TestCase {

	public function testFilterProtectedApps(): void {
		$hiddenApps = ['files', 'deck', 'activity', 'photos', 'mail'];
		$protectedApps = ['files', 'activity'];

		$filteredApps = array_values(array_diff($hiddenApps, $protectedApps));
		$ignoredProtected = array_values(array_intersect($hiddenApps, $protectedApps));

		$this->assertEquals(['deck', 'photos', 'mail'], $filteredApps);
		$this->assertEquals(['files', 'activity'], $ignoredProtected);
		$this->assertCount(3, $filteredApps);
		$this->assertCount(2, $ignoredProtected);
	}

	public function testJsonValidation(): void {
		$validJson = '["deck", "photos", "mail"]';
		$invalidJson = 'invalid-json';
		$emptyJson = '[]';

		$decoded = json_decode($validJson, true);
		$this->assertIsArray($decoded);
		$this->assertEquals(['deck', 'photos', 'mail'], $decoded);

		$decodedInvalid = json_decode($invalidJson, true);
		$this->assertNull($decodedInvalid);

		$decodedEmpty = json_decode($emptyJson, true);
		$this->assertIsArray($decodedEmpty);
		$this->assertEmpty($decodedEmpty);
	}

	public function testStringValidation(): void {
		$mixedArray = ['deck', 123, 'photos', null, 'mail', false];
		$validApps = array_values(array_filter($mixedArray, 'is_string'));

		$this->assertEquals(['deck', 'photos', 'mail'], $validApps);
		$this->assertCount(3, $validApps);

		foreach ($validApps as $app) {
			$this->assertIsString($app);
		}
	}

	public function testDuplicateRemoval(): void {
		$appsWithDuplicates = ['deck', 'photos', 'deck', 'mail', 'photos'];

		$uniqueApps = array_values(array_unique($appsWithDuplicates));

		$this->assertEquals(['deck', 'photos', 'mail'], $uniqueApps);
		$this->assertCount(3, $uniqueApps);
	}
}
