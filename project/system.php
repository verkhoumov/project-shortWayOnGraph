<?php
// Очистка проекта.
if (!empty($_POST['system']['clear']))
{
	// Очистка сессии от всех данных.
	session_destroy();
	
	// Переадрессация на главную страницу для обновления данных.
	header('Location: /', 0);
}

// Загрузка задачи.
if (!empty($_POST['system']['start_pack']))
{
	setDataToSession('points_list', $config['start_pack']['points']);
	setDataToSession('relations_list', $config['start_pack']['relations']);
}