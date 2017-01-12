<?php
/**
 *  Обработка записи и генерации опорных точек.
 */
// Данные по-умолчанию.
$data['points'] = [
	'list'  => [],
	'text'  => '',
	'error' => ''
];

// Получение данных из сессии.
$data['points']['list'] = getDataFromSession('points_list', 'array');
$data['points']['text'] = getArrayListAsText($data['points']['list']);

if (!empty($_POST['graph']['points']))
{
	$points = (array) $_POST['graph']['points'];

	// Сохранение списка из формы.
	if (!empty($points['save']))
	{
		$points_list = (string) $points['list'];

		// Обработка списка опорных точек.
		$data['points']['list'] = getGraphPointsListFromString($points_list);
	}

	// Генерация списка опорных точек.
	if (!empty($points['generate']['submit']))
	{
		$points_size = (integer) $points['generate']['size'];

		if ($points_size > 0)
		{
			// Генерация списка опорных точек.
			$data['points']['list'] = getRandomGraphPointsList($points_size);
		}
	}

	$data['points']['text'] = getArrayListAsText($data['points']['list']);
	
	// Сохранить данные в сессию.
	setDataToSession('points_list', $data['points']['list']);

	if (empty($data['points']['text']))
	{
		$data['points']['error'] = '<div class="alert alert-danger" role="alert">Ни одной опорной точки не указано!</div>';
	}
}