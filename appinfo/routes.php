<?php

return [
	'ocs' => [
		[
			'name' => 'stats#getStats',
			'url' => '/api/v1/stats',
			'verb' => 'GET',
		],
	],
	'resources' => [],
	'routes' => [
		[
			'name' => 'adminapi#getApps',
			'url' => '/api/admin/apps',
			'verb' => 'GET'
		],
		[
			'name' => 'adminapi#setHidden',
			'url' => '/api/admin/hidden',
			'verb' => 'POST'
		],
	]
];
