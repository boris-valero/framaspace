<?php
/**
 * Template CSS pour masquer les applications sélectionnées
 */
declare(strict_types=1);

// Validation des données
if (!isset($_['hidden_apps']) || !is_array($_['hidden_apps'])) {
    return;
}

$hiddenApps = $_['hidden_apps'];

// Génération des règles CSS pour masquer les applications
foreach ($hiddenApps as $appId): 
    if (!is_string($appId) || empty($appId)) continue;
    $escapedAppId = addcslashes($appId, '"\\');
?>
/* Masquer l'application <?php p($appId); ?> dans tous les menus */

/* Menu principal dans le header (barre du haut) */
#appmenu li[data-id="<?php p($escapedAppId); ?>"],
.header-appsmenu li[data-id="<?php p($escapedAppId); ?>"],
.header-menu .header-menu__entry[data-app="<?php p($escapedAppId); ?>"],
.apps-menu .app-entry[data-app="<?php p($escapedAppId); ?>"],
.app-grid .app-entry[data-app="<?php p($escapedAppId); ?>"],
#header .menutoggle + .menu li[data-id="<?php p($escapedAppId); ?>"] {
    display: none !important;
}

/* Navigation latérale (sidebar) */
#app-navigation .app-navigation-entry[data-id="<?php p($escapedAppId); ?>"],
nav .app-navigation-entry[data-id="<?php p($escapedAppId); ?>"],
.app-navigation li[data-id="<?php p($escapedAppId); ?>"] {
    display: none !important;
}

/* Liens directs vers les applications */
a[href*="/apps/<?php p($escapedAppId); ?>/"],
a[href*="index.php/apps/<?php p($escapedAppId); ?>"],
a[href$="apps/<?php p($escapedAppId); ?>"] {
    display: none !important;
}

/* Vue.js et composants modernes */
[data-app-id="<?php p($escapedAppId); ?>"],
[data-app="<?php p($escapedAppId); ?>"],
.app-navigation-vue [data-app="<?php p($escapedAppId); ?>"],
.vue-component[data-app="<?php p($escapedAppId); ?>"] {
    display: none !important;
}

/* Menu déroulant des applications */
.app-menu-main .app-entry[data-app="<?php p($escapedAppId); ?>"],
.app-menu .app-entry[data-app="<?php p($escapedAppId); ?>"] {
    display: none !important;
}

<?php endforeach; ?>

/* Force l'affichage correct du menu restant */
#appmenu {
    display: flex !important;
}

#appmenu li:not([style*="display: none"]) {
    display: inline-block !important;
}