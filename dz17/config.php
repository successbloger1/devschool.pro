<?php

//Включаем отображение ошибок
error_reporting(E_ERROR | E_NOTICE | E_PARSE/* |E_WARNING|E_ALL */);
ini_set('display_erorrs', 1);

header('Content-Type: text/html; charset=utf-8');

define('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);

// ---------- Подключаем автозагрузчик ------

spl_autoload_register('autoload');

function autoload($className) {
    $fileName = __DIR__ . '/lib/' . $className . '.php';
    if (file_exists($fileName)) {
        require_once($fileName);
        return true;
    }
    return false;
}

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
require_once './lib/FirePHPCore/FirePHP.class.php';

// Инициализируем класс FirePHP
$firephp = FirePHP::getInstance(true);

//Устанавливаем активность
$firephp->setEnabled(true);

if (!(basename($_SERVER['REQUEST_URI']) == 'install.php')) {
    
    if (file_exists('db.php')) {
        require_once 'db.php';
    } else {
        header('Location: install.php');
    }

    // ---------- Configuration dbSimple ----------
    ## Подключение к БД.
    require_once "./lib/dbsimple/config.php";
    require_once "DbSimple/Generic.php";

    // Подключаемся к БД.
    $db = DbSimple_Generic::connect("mysqli://$user_name:$password@$server_name/$database");

    // Устанавливаем обработчик ошибок.
    $db->setErrorHandler('databaseErrorHandler');

    // Код обработчика ошибок SQL.
    function databaseErrorHandler($message, $info) {
        // Если использовалась @, ничего не делать.
        if (!error_reporting())
            return;
        // Выводим подробную информацию об ошибке.
        echo "SQL Error: $message<br><pre>";
        if ($info['code'] == 1049 || $info['code'] == 1045 || $info['code'] == 2005) {
            echo "Произвести установку снова: <a href='install.php'>Install</a>";
        }
        echo "</pre>";
        exit;
    }

    // Подключаем логирование запросов
    //$db->setLogger('myLogger');
    // Настраиваем вывод логов в консоль FirePHP 
    function myLogger($db, $sql, $caller) {
        global $firephp;

        if (isset($caller['file'])) {
            $firephp->group("at " . @$caller['file'] . ' line ' . @$caller['line']);
        }
        $firephp->log($sql);
        if (isset($caller['file'])) {
            $firephp->groupEnd();
        }
    }

    // Инициализируем AdsStore
    $main = AdsStore::instance();
    $main->getAllAdsFromDb();
}
