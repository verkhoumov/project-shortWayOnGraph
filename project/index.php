<?php session_start();

ini_set('memory_limit', '1000M');

// Исходные данные и функции.
require_once 'config.php';
require_once 'functions.php';
require_once 'system.php';

/**
 *  Обработчики форм.
 */
// Опорные точки графа.
require_once 'graph.points.php';

// Создание связей между опорными точками.
require_once 'graph.relations.php';

// Поиск решения.
require_once 'graph.search.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>МОЭ, Верхоумов Д.О.</title>

	<meta name="description" content="Методы оптимизации в экономике. Решение транспортной задачи методом Бэллмана-Форда.">
	<meta name="author" content="Dmitriy Verkhoumov">
	<meta name="robots" content="index,follow">

	<!-- Как отображать контент в браузерах -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

	<!-- Файлы стилей по-умолчанию -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	
	<!-- Индивидуальные файлы стилей -->
	<link rel="stylesheet" href="/style.css">
</head>

<body>
	<form method="POST" id="setPoints" action="#points"></form>
	<form method="POST" id="getPoints" action="#points"></form>
	<form method="POST" id="setRelations" action="#relations"></form>
	<form method="POST" id="getSolution" action="#solution"></form>
	<form method="POST" id="initSystem" action="#system"></form>

	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default" id="system">
					<div class="panel-body">
						<div class="form-group">
							<button name="system[clear]" form="initSystem" type="submit" value="1" class="btn btn-block btn-danger"><span class="glyphicon glyphicon-off glyphicon-button"></span><span class="hidden-xs button-text-with-icon">Очистить проект</span></button>
						</div>

						<p class="text-muted no-margin-bottom"><i>Очистить все данные по графу: опорные точки и связи между ними.</i></p>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<button name="system[start_pack]" form="initSystem" type="submit" value="1" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-share glyphicon-button"></span><span class="hidden-xs button-text-with-icon">Загрузить задачу</span></button>
						</div>

						<p class="text-muted no-margin-bottom"><i>Загрузить опорные точки и связи между ними по задаче, решённой на занятии.</i></p>
					</div>
				</div>
				
				<div class="panel panel-default" id="points">
					<div class="panel-heading">Опорные точки графа</div>
					
					<div class="panel-body">
						<?php echo empty($data['points']['error']) ? '' : $data['points']['error']; ?>

						<div class="form-group">
							<textarea name="graph[points][list]" form="setPoints" class="form-control" rows="5" placeholder="A, B, C, D, E, F, G"><?php echo empty($data['points']['text']) ? '' : $data['points']['text']; ?></textarea>
						</div>

						<p class="text-muted no-margin-bottom"><i>Перечислите опорные точки через запятую, чтобы использовать их для добавления в граф.</i></p>
					</div>

					<div class="panel-body">
						<button name="graph[points][save]" form="setPoints" type="submit" value="1" class="btn btn-block btn-success"><span class="glyphicon glyphicon-floppy-disk glyphicon-button"></span><span class="hidden-xs button-text-with-icon">Сохранить</span></button>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<div class="row">
								<div class="col-xs-8">
									<input name="graph[points][generate][size]" form="getPoints" type="text" class="form-control" placeholder="Размер графа">
								</div>
								
								<div class="col-xs-4">
									<button name="graph[points][generate][submit]" form="getPoints" type="submit" value="1" class="btn btn-block btn-default"><span class="glyphicon glyphicon-repeat glyphicon-button"></span></button>
								</div>
							</div>
						</div>

						<p class="text-muted no-margin-bottom"><i>Сгенерировать набор случайных опорных точек для графа.</i></p>
					</div>
				</div>
				
				<div class="panel panel-success" id="relations">
					<div class="panel-heading">Добавление рёбер графа</div>

					<div class="panel-body points-list-x-y relations">
						<?php echo empty($data['relations']['error']) ? '' : $data['relations']['error']; ?>
						
						<div class="row">
							<div class="col-xs-4">
								<select name="graph[relations][points][start]" form="setRelations" class="form-control points-list-x">
									<?php echo getGraphPointsSelectList(); ?>
								</select>
							</div>
							
							<div class="col-xs-4">
								<select name="graph[relations][points][direction]" form="setRelations" class="form-control">
									<option value="forward">→</option>
									<option value="backward">←</option>
								</select>
							</div>
							
							<div class="col-xs-4">
								<select name="graph[relations][points][end]" form="setRelations" class="form-control points-list-y">
									<?php echo getGraphPointsSelectList(); ?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="panel-body">
						<div class="form-group">
							<input name="graph[relations][cost]" form="setRelations" type="text" class="form-control" placeholder="Вес, например: 100">
						</div>

						<span class="help-block">Вес ребра.</span>
					</div>
					
					<div class="panel-body">
						<button name="graph[relations][add]" value="1" form="setRelations" type="submit" class="btn btn-block btn-success"><span class="glyphicon glyphicon-plus glyphicon-button"></span><span class="hidden-xs button-text-with-icon">Добавить ребро</span></button>
					</div>
				</div>
				
				<div class="panel panel-info">
					<div class="panel-heading">Рёбра графа</div>
						
					<?php if (!empty($data['relations']['list']) && count($data['relations']['list'])): ?>
					<table class="table table-striped table-bordered no-margin-bottom">
						<thead>
							<tr>
								<td class="text-center"><b>X</b></td>
								<td class="text-center"><b>Куда</b></td>
								<td class="text-center"><b>Y</b></td>
								<td class="text-center"><b>Цена</b></td>
								<td class="text-center"><b>Удалить</b></td>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($data['relations']['list'] as $relation_id => $relation): ?>
							<tr>
								<td class="text-center"><?php echo $data['points']['list'][$relation['start_id']]; ?></td>
								<td class="text-center"><?php echo ($relation['direction'] == 'forward' ? '→' : '←'); ?></td>
								<td class="text-center"><?php echo $data['points']['list'][$relation['end_id']]; ?></td>
								<td class="text-center"><?php echo $relation['cost']; ?></td>
								<td><button name="graph[relations][delete]" form="setRelations" value="<?php echo $relation_id; ?>" type="submit" class="btn btn-block btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle glyphicon-button"></span></button></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php else: ?>
					<div class="panel-body">
						<p class="no-margin-bottom">Ни одного ребра не задано!</p>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="col-md-8">
				<div class="panel panel-primary" id="solution">
					<div class="panel-heading">Решение транспортной задачи методом Беллмана-Форда</div>

					<div class="panel-body">
						<p class="text-muted no-margin-bottom">Программа произведёт поиск самого дешевого маршрута из точки <code>X</code> до точки <code>Y</code>.</p>
					</div>

					<div class="panel-body">
						<?php echo empty($data['search']['error']) ? '' : $data['search']['error']; ?>

						<div class="form-group points-list-x-y search">
							<div class="row">
								<div class="col-xs-6">
									<div class="input-group">
  										<div class="input-group-addon">X</div>
										<select name="graph[search][points][start]" form="getSolution" class="form-control points-list-x">
											<?php echo getGraphPointsSelectList($data['search']['start_id']); ?>
										</select>
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="input-group">
  										<div class="input-group-addon">Y</div>
										<select name="graph[search][points][end]" form="getSolution" class="form-control points-list-y">
											<?php echo getGraphPointsSelectList($data['search']['end_id']); ?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<span class="help-block">Начало и конец маршрута.</span>
					</div>
					
					<div class="panel-body">
						<button name="graph[search][submit]" value="1" form="getSolution" type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-search glyphicon-button"></span><span class="hidden-xs button-text-with-icon">Найти решение</span></button>
					</div>

					<?php if ($data['search']['result']): ?>
					<table class="table table-striped table-bordered no-margin-bottom">
						<thead>
							<tr>
								<td><b>Кратчайший маршрут</b></td>
								<td><b>Стоимость</b></td>
							</tr>
						</thead>
						
						<tbody>					
						<?php
						if (!empty($_POST['graph']['search']))
						{	
							foreach ($data['search']['result']['routing'] as $point_id)
							{
								$view['route'][] = $data['points']['list'][$point_id + 1];
							}
							
							// Вывод на экран.
							echo '<tr class="success"><td>'.implode(' → ', $view['route']).'</td><td>'.$data['search']['result']['cost'].'</td></tr>';
						}
						else
						{
							echo '<tr><td colspan="2"><p class="text-danger text-center no-margin-bottom">Ни одного маршрута из точки <code>X</code> в точку <code>Y</code> не найдено!</p></td></tr>';
						}
						?>
						</tbody>
					</table>
					<?php endif; ?>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Визуализация графа</div>

					<div class="panel-body">
						<p class="text-muted no-margin-bottom"><span class="text-success">Зелёные линии</span> - кратчайший путь.<br><span class="text-default">Серые линии</span> - рёбра графа.</p>
					</div>

					<div class="panel-body">
						<div id="canvas"></div>
					</div>
				</div>
			</div>

			<div class="col-md-12 footer">
				<p class="no-margin-bottom">Автор: <a href="https://vk.com/verkhoumov" target="_blank">Верхоумов Д.О.</a><br>Версия от: 12.01.2017 20:33<br>Вы можете <a href="https://github.com/verkhoumov/project_moe" target="_blank" title="Перейти в репозиторий проекта">изучить и скачать исходный код проекта</a> на GitHub.</p>
			</div>
		</div>
	</div>
	
	<!-- JS-файлы по-умолчанию -->
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- Визуализатор графов -->
	<script type="text/javascript" src="/dracula-js/js/raphael-min.js"></script>
    <script type="text/javascript" src="/dracula-js/js/dracula_graffle.js"></script>
    <script type="text/javascript" src="/dracula-js/js/dracula_graph.js"></script>
	
	<!-- Индивидуальные JS-файлы -->
	<script type="text/javascript">
	var relations = jQuery.parseJSON('<?php echo json_encode(getNormalizeRelations(), JSON_FORCE_OBJECT); ?>');
	</script>

	<script src="/script.js"></script>
</body>
</html>