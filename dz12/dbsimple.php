<?php

## Подключение к БД.
require_once "./dbsimple/config.php";
require_once "DbSimple/Generic.php";

// Подключаемся к БД.
$db = DbSimple_Generic::connect("mysqli://$user_name:$password@$server_name/$database");

// Устанавливаем обработчик ошибок.
$db->setErrorHandler('databaseErrorHandler');

// Код обработчика ошибок SQL.
function databaseErrorHandler($message, $info)
{
    // Если использовалась @, ничего не делать.
    if (!error_reporting()) return;
    // Выводим подробную информацию об ошибке.
    echo "SQL Error: $message<br><pre>"; 
    if ($info['code'] == 1049 || $info['code'] == 1045 || $info['code'] == 2005 ){
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
