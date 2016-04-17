<?php

if (file_exists('db.php')){
    require_once './db.php';
} else {
    header('Location: install.php');
}

require_once './config.php';
require_once './functions.php';
require_once './dbsimple.php';


// ------------------------------------------------------------

if (isset($_GET['delete'])) {
    delete_ads();
}

if (!empty($_POST)) {
    if (!isset($_POST['new'])) {
        $valid = validation($_POST);

        if (!empty($valid['err'])) {
            $smarty->assign('mas', form_data($valid['valid'], $valid['err']));
        } else {
            add_ad($valid['valid']);
        }
        
    }
} else {
    if (isset($_GET['id'])) {
        $smarty->assign('mas', form_data($_GET['id']));
    } else {
        $smarty->assign('mas', form_data());
    }
}

$smarty->assign('citys', form_construct('location'));
$smarty->assign('category', form_construct('category'));

if (isset($_GET['sort'])) {
    $smarty->assign('print_ads', print_ads($_GET['sort']));
} else {
    $smarty->assign('print_ads', print_ads());
}

// ------------------------------------------------------------

$smarty->display('index.tpl');
