<?php

if (file_exists('db.php')){
    require_once './db.php';
} else {
    header('Location: install.php');
}

require_once './config.php';
require_once './dbsimple.php';

require './Form.php';
require './Ad.php';

// ------------------------------------------------------------

$form = new Form();

if (!empty($_POST) && !isset($_POST['new'])) {

    $ad = new Ad($form->validation($_POST));

    if ($form->getErrors() != '') {
        $form->assignAd($ad);
//        $form->getErrors();
    } else {
        if (isset($_POST['create'])) {
            $ad->addAd();
        }
        if (isset($_POST['save'])) {
            $ad->saveAd();
        }
        unset($_POST);
        header('Location: ' . SCRIPT_NAME);
    }
} 

if (isset($_GET['id'])) {
    $ad = new Ad();
    $form->assignAd($ad);
}

if (isset($_GET['delete'])) {
    if ($_GET['delete'] == '0') {
        Ad::deleteAllAds();
    } else {
        $ad = new Ad();
        $ad->deleteAd();
    }
    header('Location: ' . SCRIPT_NAME);
}

if (isset($_GET['sort'])) {
    $form->assignSortAdsList(Ad::getSortAdsList($_GET['sort']));
} else {
    $form->assignAdsList(Ad::getAdsList());
}

$form->show();

