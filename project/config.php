<?php
$config = [
	// Сессия
	'session_prefix' => 'moe__',

	// Загрузочный пакет задачи
	'start_pack' => [
		// Опорные точки
		'points' => [1 => 'Точка-1', 'Точка-2', 'Точка-3', 'Точка-4', 'Точка-5', 'Точка-6', 'Точка-7'],

		// Связи между опорными точками
		'relations' => [
			['start_id' => 1, 'end_id' => 2, 'direction' => 'forward', 'cost' => 2],
			['start_id' => 1, 'end_id' => 3, 'direction' => 'forward', 'cost' => 4],
			['start_id' => 1, 'end_id' => 4, 'direction' => 'forward', 'cost' => 10],
			['start_id' => 2, 'end_id' => 5, 'direction' => 'forward', 'cost' => 5],
			['start_id' => 2, 'end_id' => 4, 'direction' => 'forward', 'cost' => 11],
			['start_id' => 3, 'end_id' => 4, 'direction' => 'forward', 'cost' => 3],
			['start_id' => 3, 'end_id' => 6, 'direction' => 'forward', 'cost' => 1],
			['start_id' => 4, 'end_id' => 5, 'direction' => 'forward', 'cost' => 8],
			['start_id' => 4, 'end_id' => 6, 'direction' => 'forward', 'cost' => 7],
			['start_id' => 5, 'end_id' => 7, 'direction' => 'forward', 'cost' => 6],
			['start_id' => 6, 'end_id' => 7, 'direction' => 'forward', 'cost' => 9]
		]
	]
];