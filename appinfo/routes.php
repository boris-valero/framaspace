<?php
/**
 * Routes de l'application FramaSpace
 */

return [
    'routes' => [
        ['name' => 'admin#setHiddenApps', 'url' => '/admin/hidden-apps', 'verb' => 'POST'],
        ['name' => 'css#hiddenApps', 'url' => '/css/hidden-apps', 'verb' => 'GET'],
    ],
];