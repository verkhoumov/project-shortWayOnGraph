<?php
/**
 *  Обработка записи и генерации опорных точек.
 */
// Данные по-умолчанию.
$data['relations'] = [
	'list'      => [],
	'text'      => '',
	'start'     => '',
	'end'       => '',
	'direction' => '',
	'cost'      => 0,
	'error'     => ''
];

// Получение данных из сессии.
$data['relations']['list'] = getDataFromSession('relations_list', 'array');
$data['relations']['text'] = getArrayListAsText($data['relations']['list']);

if (!empty($_POST['graph']['relations']))
{
	$relation = (array) $_POST['graph']['relations'];

	// Добавление новой связи между опорными точками.
	if (!empty($relation['add']))
	{
		$relation_start     = (integer) $relation['points']['start'];
		$relation_end       = (integer) $relation['points']['end'];
		$relation_direction = (string) $relation['points']['direction'];
		$relation_cost      = (integer) $relation['cost'];
		
		if ($relation_cost == '')
		{
			$relation_cost = 0;
		}
		
		// Проверка данных.
		if (empty($data['points']['list'][$relation_start]) || empty($data['points']['list'][$relation_end]) || ($relation_direction != 'forward' && $relation_direction != 'backward') || $relation_start == $relation_end)
		{
			$data['relations']['error'] = '<div class="alert alert-danger" role="alert">Связь не установлена! Данные указаны некорректно!</div>';
		}
		else
		{
			$result_direction = [
				'start_id'  => $relation_start,
				'end_id'    => $relation_end,
				'direction' => $relation_direction,
				'cost'      => $relation_cost
			];
			
			$check_copy = array_search($result_direction, $data['relations']['list']);
			
			// Проверка, не существует ли такая связь.
			if ($check_copy === FALSE)
			{
				$data['relations']['list'][] = $result_direction;
			}
			else
			{
				$data['relations']['error'] = '<div class="alert alert-danger" role="alert">Связь уже существует!</div>';	
			}
		}
		
		// Сохранить данные в сессию.
		setDataToSession('relations_list', $data['relations']['list']);
	}
	
	// Удаление связи между двумя опорными точками.
	if (isset($relation['delete']))
	{
		$relation_id = (integer) $relation['delete'];
		
		// Удаляем связь.
		unset($data['relations']['list'][$relation_id]);
		
		// Сохранить данные в сессию.
		setDataToSession('relations_list', $data['relations']['list']);
	}
}