<?php

require_once './config.php';
require_once './functions.php';

// ------------------------------------------------------------

db_connect();

if (isset($_GET['delete'])) {
    delete_ads();
}

if (!empty($_POST)) {

    if (isset($_POST['new'])) {
        header('Location: ' . SCRIPT_NAME);
    } else {

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

$smarty->assign('citys', form_construct(LOCATION_QUERY));
$smarty->assign('category', form_construct(CATEGORY_QUERY));

$smarty->assign('print_ads', print_ads());

mysql_close();

// ------------------------------------------------------------

$smarty->display('index.tpl');
