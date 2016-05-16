<?php

//Включаем отображение ошибок
error_reporting(E_ERROR|E_NOTICE|E_PARSE|E_WARNING|E_ALL);
ini_set('display_erorrs', 1);

define('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);
define('LOCATION_QUERY', "SELECT id, name FROM location");
define('CATEGORY_QUERY', "SELECT id, name FROM category");


// ---------- Configuration Smarty ----------

require './smarty/libs/Smarty.class.php';

$smarty = new Smarty();

$smarty->compile_check = true;
//$smarty->debugging = true;

$smarty->template_dir = './smarty/templates/';
$smarty->compile_dir = './smarty/templates_c/';
$smarty->cache_dir = './smarty/cache/';
$smarty->config_dir = './smarty/configs/';

// ---------- Configuration DB ----------

$mysqli = new mysqli($server_name, $user_name, $password, $database);
if ($mysqli->connect_errno) {
    echo 'Не удалось подключиться к MySQL: ' . $mysqli->connect_error;
    exit;
}
if (!$mysqli->set_charset("utf8")) {
    echo 'Не удалось установить кодировку: ' . $mysqli->error;
    exit;
}