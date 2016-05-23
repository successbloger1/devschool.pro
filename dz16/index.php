<?php

require_once 'config.php';

if (isset($_GET['sort'])) {
    $main->sortAds($_GET['sort']);
} 

$main->printAds();

$smarty->display("index.tpl.html");