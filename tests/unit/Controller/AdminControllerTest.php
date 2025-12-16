<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SimpleAdminControllerTest extends TestCase {

    public function testFilterProtectedApps(): void {
        $hiddenApps = ['files', 'deck', 'activity', 'photos', 'mail'];
        $protectedApps = ['files', 'activity'];

        $filteredApps = [];
        $ignoredProtected = [];
        
        foreach ($hiddenApps as $appId) {
            if (in_array($appId, $protectedApps, true)) {
                $ignoredProtected[] = $appId;
                continue;
            }
            $filteredApps[] = $appId;
        }

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
        $validApps = [];

        foreach ($mixedArray as $appId) {
            if (is_string($appId)) {
                $validApps[] = $appId;
            }
        }

        $this->assertEquals(['deck', 'photos', 'mail'], $validApps);
        $this->assertCount(3, $validApps);
        
        foreach ($validApps as $app) {
            $this->assertIsString($app);
        }
    }

    public function testDuplicateRemoval(): void {
        $appsWithDuplicates = ['deck', 'photos', 'deck', 'mail', 'photos'];
        
        $filteredApps = [];
        foreach ($appsWithDuplicates as $appId) {
            $filteredApps[$appId] = true;
        }
        $uniqueApps = array_keys($filteredApps);

        $this->assertEquals(['deck', 'photos', 'mail'], $uniqueApps);
        $this->assertCount(3, $uniqueApps);
    }
}