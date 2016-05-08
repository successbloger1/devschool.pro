<?php

//Включаем отображение ошибок
error_reporting(E_ERROR|E_NOTICE|E_PARSE/*|E_WARNING|E_ALL*/);
ini_set('display_erorrs', 1);
header('Content-Type: text/html; charset=utf-8');

define('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);

// ---------- Configuration Smarty ----------

require_once './smarty/libs/Smarty.class.php';

$smarty = new Smarty();

$smarty->compile_check = true;
//$smarty->debugging = true;

$smarty->template_dir = './smarty/templates/';
$smarty->compile_dir = './smarty/templates_c/';
$smarty->cache_dir = './smarty/cache/';
$smarty->config_dir = './smarty/configs/';

// ---------- Configuration FirePHP ----------

// Подключаем библиотеку
require_once './FirePHPCore/FirePHP.class.php';

// Инициализируем класс FirePHP
$firephp = FirePHP::getInstance(true);

//Устанавливаем активность
$firephp->setEnabled(true);
