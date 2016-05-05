<?php

//Включаем отображение ошибок
error_reporting(E_ERROR | E_NOTICE | E_PARSE | E_WARNING | E_ALL);
ini_set('display_erorrs', 1);

//ini_set('memory_limit', 0);

ini_set('max_input_time', 0);
set_time_limit(0);
ignore_user_abort(true);

chdir(__DIR__);

require_once 'config.php';

require_once 'Offer.php';
require_once 'Worker.php';
require_once 'Vk.php';


$vk = new Vk(['access_token' => $token]);
$work = new Worker($group_id);

//Выгружаем данные из XML
$parse = $work->parseXml($xml);

// Выгружаем данные из VK
$parse_vk = $work->parseMarket();

if (empty($parse_vk)) {
    // Если в VK пусто, заливаем все товары
    $work->addAllToMarket($parse, $category_id);
} else {
    // Удалить товары из ВК, которых нет в файле synchro.php
    foreach ($parse_vk as $value) {
        $work->deleteFromMarket($group_id, $value->id);
    }
    // Проверяем наличие товара из XML в файле synchro.php
    foreach ($parse as $offer) {
        $id = $work->inSynchrofile($offer);
        if ($id === false) {
            // Если товара в фале нет, добавляем его в VK
            $work->addToMarket($offer, $category_id);
//                break;
        } elseif (!empty($id)) {
            // Если товар есть, редактируем при условии, что время синхронизации не совпадают
            $work->editMarket($offer, $category_id, $id);
//                break;
        }
    }
    
    // Удаляем товары которые есть в файле synchro.php, но нет в текущем XML
    $work->deleteNotSynchronized();
}

//Сохраняем файл синхронизации
$work->saveSynchro();
?>
