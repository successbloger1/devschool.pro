<?php

if (file_exists('db.php')){
    require_once 'db.php';
} else {
    header('Location: install.php');
}

require_once 'config.php';
require_once 'dbsimple.php';

// ------------------------------------------------------------

$main = AdsStore::instance();
$main->getAllAdsFromDb();

if (!empty($_POST) && !isset($_POST['new'])) {

    if (isset($_GET['id'])) {
        $data = array_merge(array('id' => $_GET['id']), $_POST);
    } else {
        $data = $_POST;
    }
    
    $ad = new Ad($data);
    
    if ($ad->getErrors() != '') {
        echo json_encode($ad->getErrors());
    } elseif(!isset($_POST['new'])) {
        $ad->save();
        echo json_encode($ad);
    }
    exit;
} 

if (isset($_GET['id'])) {
    $ad = $main->getAd($_GET['id']);
    echo json_encode($ad);
    exit;
}

if (isset($_GET['delete'])) {
    if ($_GET['delete'] == '0') {
        $main->deleteAllAds();
    } else {
        $main->getAd($_GET['delete'])->delete();
    }
    exit;
}

if (isset($_GET['sort'])) {
    $main->sortAds($_GET['sort']);
} 

$main->printAds();
$smarty->display("index.tpl.html");

