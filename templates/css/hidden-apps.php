<?php
/**
 * Template CSS pour masquer les applications sélectionnées - VERSION FINALE
 */
declare(strict_types=1);

// Validation des données
if (!isset($_['hidden_apps']) || !is_array($_['hidden_apps'])) {
	echo "/* FramaSpace: Aucune application à masquer */\n";
	return;
}

$hiddenApps = $_['hidden_apps'];

// Positions des applications dans le menu (basées sur votre inspection DOM)
$appPositions = [
	'dashboard' => 1,
	'talk' => 2,
	'files' => 3,
	'photos' => 4,
	'activity' => 5,
	'mail' => 6,
	'contacts' => 7,
	'calendar' => 8,
	'notes' => 9
];

foreach ($hiddenApps as $appId):
	if (!is_string($appId) || empty($appId)) {
		continue;
	}

	$position = $appPositions[$appId] ?? null;
	if ($position === null) {
		continue;
	}
	?>

/* Masquer complètement l'application <?php p($appId); ?> à la position <?php p($position); ?> */
li.app-menu-entry:nth-child(<?php p($position); ?>) {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    position: absolute !important;
    left: -9999px !important;
    top: -9999px !important;
    width: 0 !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
}

<?php endforeach; ?>

/* Réorganiser le menu pour éliminer les trous */
.app-menu {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 0 !important;
}

.app-menu li.app-menu-entry {
    flex: 0 0 auto !important;
    position: relative !important;
}

/* Assurer que les éléments visibles restent bien visibles */
li.app-menu-entry:not([style*="display: none"]):not([style*="position: absolute"]) {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    width: auto !important;
    height: auto !important;
}