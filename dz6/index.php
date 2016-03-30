<?php

require_once 'functions.php';

// Точка входа
session_start();

// Добавление объявления в сессию
add_ad();

// Удаление объявления
delete_ads();

// Вывод формы объявления
if (isset($_GET['id'])){
    show_ads($_SESSION['ads'][$_GET['id']]);
} else {
    show_ads();
}

// Список объявлений
echo '<hr>';
echo '<h2><center>Объявления</center></h2><br>';

print_ads();

?>

