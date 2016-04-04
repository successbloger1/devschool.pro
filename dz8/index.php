<?php

//Включаем отображение ошибок
error_reporting(E_ERROR|E_NOTICE|E_PARSE|E_WARNING|E_ALL);
ini_set('display_erorrs', 1);

define('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);
define('FILE_NAME', 'ads.txt');

require_once './functions_files.php';
require './smarty/libs/Smarty.class.php';

$smarty = new Smarty();

$smarty->compile_check = true;
//$smarty->debugging = true;

$smarty->template_dir = './smarty/templates/';
$smarty->compile_dir = './smarty/templates_c/';
$smarty->cache_dir = './smarty/cache/';
$smarty->config_dir = './smarty/configs/';

// ------------------------------------------------------------

if (isset($_GET['delete'])) {
    delete_ads();
}

if (!empty($_POST)) {

    if (isset($_POST['new'])) {
        header('Location: ' . SCRIPT_NAME);
    } else {

        $valid = validation($_POST);
        $valid_err = $valid['err'];
        $valid_post = $valid['valid'];

        if (!empty($valid_err)) {
            $smarty->assign('mas', show_ads($valid_post, $valid_err));
        } else {
            add_ad($valid_post);
        }
    }
} else {
    if (isset($_GET['id'])) {
        $smarty->assign('mas', show_ads($_GET['id']));
    } else {
        $smarty->assign('mas', show_ads());
    }
}

$smarty->assign('citys', 
        array(0 => '-- Выберите город --', '641780' => 'Новосибирск', '641490' => 'Барабинск', 
              '641510' => 'Бердск', '641600' => 'Искитим', '641630' => 'Колывань', 
              '641680' => 'Краснообск', '641710' => 'Куйбышев', '641760' => 'Мошково')
);
$smarty->assign('category', 
        array(0 => '-- Выберите категорию --', 'Транспорт', 'Недвижимость', 'Работа', 
              'Услуги', 'Личные вещи', 'Для дома и дачи', 'Бытовая электроника', 'Прочее')
        );

$smarty->assign('print_ads', print_ads());

$smarty->display('index.tpl');
