<?php
/**
 *  Поиск кратчайшего (наиболее дешевого) пути.
 */
// Данные по-умолчанию.
$data['search'] = [
	'result' => [],
	'start'  => '',
	'end'    => '',
	'error'  => ''
];

if (!empty($_POST['graph']['search']))
{
	$search = (array) $_POST['graph']['search'];
	$relations = $data['relations']['list'];

	// Проверка наличия связей между опорными точками, без которых построение
	// графа не возможно.
	if (!count($relations))
	{
		$data['search']['error'] = '<div class="alert alert-danger" role="alert">Ни одной связи между опорными точками не указано!</div>';
	}
	else
	{
		$data['search']['start_id'] = (integer) $search['points']['start'];
		$data['search']['end_id']   = (integer) $search['points']['end'];
		
		$edges = getNormalEdges($relations);
		$data['search']['result'] = getEdgeSearch_BellmanFord($data['search']['start_id'], $data['search']['end_id'], $edges, $data['points']['list']);
		
		if (!$data['search']['result'])
		{
			$data['search']['error'] = '<div class="alert alert-danger" role="alert">Заданного маршрута не существует!</div>';
		}
	}
}