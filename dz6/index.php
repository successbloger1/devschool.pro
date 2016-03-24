<?php
header('Content-Type: text/html; charset=utf-8');

require_once 'functions.php';

// Точка входа
session_start();

// Добавление объявления в сессию
if (isset($_POST['main_form_submit'])){
    $_SESSION['ads'][uniqid()] = $_POST;
    unset($_POST);
    header("Location: index.php");
}

// Удаление объявления
if (isset($_GET['delete'])){
    if ($_GET['delete']==0){
        unset($_SESSION['ads']);
        header("Location: index.php"); 
    } else {
        unset($_SESSION['ads'][$_GET['delete']]);
        header("Location: index.php");
    }
}

// Вывод формы объявления
if (isset($_GET['id'])){
    show_ads($_SESSION['ads'][$_GET['id']]);
} else {
    show_ads();
}

// Список объявлений
echo '<hr>';
echo '<h2><center>Объявления</center></h2><br>';

if (!empty($_SESSION['ads'])) {
    foreach ($_SESSION as $ads) {
        foreach ($ads as $key => $value) {
            echo '<ul><center><li><a href="?id='.$key.'">'.$value['title'] 
                 .'</a>  | '.$value['price'].' руб. | '.$value['seller_name']
                 .' | <a href="?delete='.$key. '">Удалить</a></li></center></ul>';
        }
    }
    
    print ('<a href="?delete=0"><center><br>Удалить все объявления</center></a><br>');
    
} else {
    echo '<center>Объявлений нет</center>';
}


?>

