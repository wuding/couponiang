<?php
return [
	'_route' => [
		[['GET', 'POST', 'PUT'], '/[index]', '/index/index'],
		['GET', '/search', '_module/_controller/search']
	],
	'database' => array(
		'adapter' => 'Medoo',
		'driver' => 'mysql',
		'host' => 'localhost',
		'port' => 3306,
		# 'db_name' => 'com_urlnk',
		'user' => 'root',
		'password' => 'root',
	),
];
