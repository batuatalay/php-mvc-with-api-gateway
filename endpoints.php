<?php

$allEndPoints = [
	"GET" => [
		'/main',
	],
	"POST" => [
		'/main/index',
	],
];

$APIEndpoints = [
    "GET" => [
        '/API/first'
    ],

    "POST" => [
        '/API/second'
    ]
];
foreach ($APIEndpoints as $key => $value) {
	$allEndPoints[$key] = array_merge($value, $allEndPoints[$key]);
}

?>