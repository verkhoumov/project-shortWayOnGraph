<?php
/**
 *  Сгенерировать случайный набор опорных точек графа.
 *  
 *  @param   integer  $length  [Количество опорных точек]
 *  @return  array
 */
function getRandomGraphPointsList($length = 0)
{
	$length = (integer) $length;
	
	$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
	$alphabet_length = count($alphabet);

	$include_number	= FALSE;
	$result = [];

	// Если запрошено больше опорных точек, чем букв в английском алфавите,
	// нумеруем все опорные точки.
	if ($length > $alphabet_length)
	{
		$include_number = TRUE;
	}

	// Генерация списка.
	for ($i = 0, $j = 1, $k = 1; $i < $length; $i++, $j++)
	{
		$result[$i] = $alphabet[($j - 1)];

		// Добавляем число после буквы, если количество опорных точек больше,
		// чем букв в английском алфавите.
		if ($include_number)
		{
			$result[$i] .= $k;
		}

		// Если обрабатывается последняя буква алфавита, генерируем новую итерацию.
		if ($j == $alphabet_length)
		{
			// Сбрасываем счётчик на 1-ую позицию.
			$j = 0;

			// Задаём новый уровень итерации.
			++$k;
		}
	}

	return $result;
}

/**
 *  Обработка списка опорных точек из строки.
 *  
 *  @param   integer  $points  [Опорные точки через запятую]
 *  @return  array
 */
function getGraphPointsListFromString($points = '')
{
	$points = (string) $points;

	$separator = ',';
	$result = [];

	// Разделение опорных точек.
	$points_list = explode($separator, $points);

	// Удаление лишних символов, окружающих название опорной точки, например, пробелы.
	foreach ($points_list as $point)
	{
		$result[++$i] = trim($point);
	}

	return $result;
}

/**
 *  Значения массива в виде строки.
 *  
 *  @param   array   $points  [Массив]
 *  @return  string
 */
function getArrayListAsText($array = [])
{
	$array = (array) $array;

	return implode(', ', $array);
}

/**
 *  Получение данных из сессии.
 *  
 *  @param   string  $session_name  [Имя сессии]
 *  @return  all types
 */
function getDataFromSession($session_name = '', $callback_type = 'integer')
{
	global $config;

	$session_name = (string) $session_name;
	$result = '';
	
	if ($callback_type == 'array')
	{
		$result = [];
	}
	
	// Если параметр существует.
	if (!empty($_SESSION[$config['session_prefix'].$session_name]))
	{
		$result = $_SESSION[$config['session_prefix'].$session_name];
	}

	return $result;
}

/**
 *  Сохранение данных в сессию.
 *  
 *  @param  string  $session_name  [Имя сессии]
 *  @param  all types  $session_data  [Значение]
 */
function setDataToSession($session_name = '', $session_data = '')
{
	global $config;

	$session_name = (string) $session_name;

	$_SESSION[$config['session_prefix'].$session_name] = $session_data;
}

/**
 *  Список опорных точек в виде выпадющего списка.
 *  
 *  @return  string
 */
function getGraphPointsSelectList($selected = 0)
{
	global $data;

	$result = '';

	// Массив с опорными точками.
	$points_list = (array) $data['points']['list'];

	// Создание выпадающего списка.
	foreach ($points_list as $key => $point)
	{
		if ($selected == $key)
		{
			$result .= '<option value="'.$key.'" selected="selected">'.$point.'</option>';
		}
		else
		{
			$result .= '<option value="'.$key.'">'.$point.'</option>';
		}
	}

	// Если ни одной опорной точки нету.
	if ($result == '')
	{
		$result .= '<option value="0">Нет опорных точек</option>';
	}

	return $result;
}

/**
 *  Список связей лучшего маршрута.
 *  
 *  @param   array  $relations  [Связи]
 *  @return  array
 */
function getBestEdgesList($relations = [])
{
	global $data;
	
	$relations = (array) $relations;
	$result = [];
	
	$edges = $data['search']['result']['routing'];
	
	$is_result = count($edges);
	
	if ($is_result)
	{	
		foreach ($relations as $id => $relation)
		{
			for ($i = 0; $i < $is_result - 1; $i++)
			{
				if ($relation['a'] == ($edges[$i] + 1) && $relation['b'] == ($edges[$i + 1] + 1))
				{
					$result[] = $id;
				}
			}
		}
	}
	
	return $result;
}

// Представление списка связей в нормализованном под алгоритмы поиска виде,
// как список связей.
/**
 *  Представление списка связей в нормализованном под алгоритмы поиска виде.
 *  
 *  @param   array   $relations  [Связи]
 *  @return  array
 */
function getNormalEdges($relations = [])
{
	$relations = (array) $relations;
	
	$result = [];
	
	if ($relations)
	{
		foreach ($relations as $edge)
		{
			$start = $edge['start_id'];
			$end = $edge['end_id'];
			
			if ($edge['direction'] == 'backward')
			{
				$start = $edge['end_id'];
				$end = $edge['start_id'];
			}
			
			$result[] = [
				'a' => $start,
				'b' => $end,
				'cost' => $edge['cost']
			];
		}
	}
	
	return $result;
}

/**
 *  Список связей в нормализованном под JS виде.
 *  
 *  @return  array
 */
function getNormalizeRelations()
{
	global $data;

	$result = [];
	$relations = getNormalEdges($data['relations']['list']);
	
	// Cписок рёбер лучшего маршрута.
	$fill_relations = getBestEdgesList($relations);
	
	if ($relations)
	{
		foreach ($relations as $id => $relation)
		{
			$start = $relation['a'];
			$end = $relation['b'];
	
			$result[] = [
				'start' => $data['points']['list'][$start],
				'end'   => $data['points']['list'][$end],
				'cost'  => $relation['cost'],
				'fill'  => in_array($id, $fill_relations)
			];
		}
	}

	return $result;
}

/**
 *  Построение кратчайшего маршрута с помощью алгоритма Беллмана-Форда.
 *  
 *  @param   integer  $start   [Начальная точка]
 *  @param   integer  $end     [Конечная точка]
 *  @param   array  $edges     [Список рёбер]
 *  @param   array  $points    [Список точек]
 *  @return  array
 */
function getEdgeSearch_BellmanFord($start = 0, $end = 0, $edges = [], $points = [])
{
	$start  = (integer) $start;
	$end    = (integer) $end;
	$edges  = (array) $edges;
	$points = (array) $points;

	$result = [
		'routing' => [],
		'cost' => 0
	];
	
	// Количество вершин.
	$n = count($points);
	
	// Количество рёбер.
	$m = count($edges);
	
	// Начальная точка.
	$v = $start - 1;
	
	// Конечная точка.
	$t = $end - 1;
	
	// Массив расстояний.
	$d = array_fill(0, $n, INF);
	
	// Расстояние от начальной точки равно нулю.
	$d[$v] = 0;
	
	// Массив с предками для каждой вершины.
	$p = array_fill(0, $n, -1);
	
	// Карта маршрутизации.
	$path = [];
	
	for (;;) // Бесконечный цикл.
	{
		// Ни одного решения нету.
		// Если потом после прохода всех рёбер не будет найдено ни одного решения,
		// программа закончит работу.
		$any = FALSE;
		
		// Проходим по каждому ребру.
		for ($j = 0; $j < $m; $j++)
		{
			// Если (в первой итерации) мы наткнулись на стартовое ребро.
			// Если в любой другой итерации мы наткнулись на ребро, до которого построен кратчайший маршрут.
			if ($d[$edges[$j]['a'] - 1] < INF)
			{
				// Если расстояние до конца ребра больше, чем до начала + стоимость ребра, то:
				if ($d[$edges[$j]['b'] - 1] > ($d[$edges[$j]['a'] - 1] + $edges[$j]['cost']))
				{
					// Задаём дистанцию до конца ребра как расстояние до начала ребра + вес текущего ребра.
					$d[$edges[$j]['b'] - 1] = $d[$edges[$j]['a'] - 1] + $edges[$j]['cost'];
					
					// Запоминаем для конца текущего ребра его предка (начало ребра).
					$p[$edges[$j]['b'] - 1] = $edges[$j]['a'] - 1;
					
					// Решение найдено.
					$any = TRUE;
				}
			}
		}
		
		// Если решение так и не было найдено, выходим из бесконечного цикла.
		if (!$any)
		{
			break;
		}
	}
	
	// Если найдено расстояние до конечной точки, формируем маршрут.
	if ($d[$t] != INF)
	{	
		// Проходим по всем предкам для конечной точки маршрута.
		for ($current = $t; $current != -1; $current = $p[$current])
		{
			// Запоминаем опорную точку в карту маршрутизации от X до Y.
			$path[] = $current;
		}
		
		// Разворачиваем карту маршрутизации, так как она была построена в обратном порядке.
		$result['routing'] = array_reverse($path);
		
		// Стоимость маршрута до выбранной точки.
		$result['cost'] = $d[$t];
	}
	
	return $result;
}