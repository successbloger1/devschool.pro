<?php
/*
 * Следующие задания требуется воспринимать как ТЗ (Техническое задание)
 * p.s. Разработчик, помни! 
 * Лучше уточнить ТЗ перед выполнением у заказчика, если ты что-то не понял, чем сделать, переделать, потерять время, деньги, нервы, репутацию.
 * Не забывай о навыках коммуникации :)
 * 
 * Задание 1
 * - Создайте массив $date с пятью элементами
 * - C помощью генератора случайных чисел забейте массив $date юниксовыми метками
 * - Сделайте вывод сообщения на экран о том, какой день в сгенерированном массиве получился наименьшим, а какой месяц наибольшим
 * - Отсортируйте массив по возрастанию дат
 * - С помощью функция для работы с массивами извлеките последний элемент массива в новую переменную $selected
 * - C помощью функции date() выведите $selected на экран в формате "дд.мм.ГГ ЧЧ:ММ:СС"
 * - Выставьте часовой пояс для Нью-Йорка, и сделайте вывод снова, чтобы проверить, что часовой пояс был изменен успешно
 * 

 */

//Включаем отображение ошибок
error_reporting(E_ERROR|E_NOTICE|E_PARSE|E_WARNING);
ini_set('display_erorrs', 1);

// Создаем массив из 5 элементов и заполняем его юниксовыми метками
$date = array (
    rand(1, time()),
    rand(1, time()),
    rand(1, time()),
    rand(1, time()),
    rand(1, time())
    );

/*
//Вывод массива
echo date('Y.m.d',$date[0]).'<br>'
    .date('Y.m.d',$date[1]).'<br>'
    .date('Y.m.d',$date[2]).'<br>'
    .date('Y.m.d',$date[3]).'<br>'
    .date('Y.m.d',$date[4]).'<br><br>';
*/

// Наибольший месяц и наименьший день
echo 'Наименьший день: '.min(date('d',$date[0]),date('d',$date[1]),date('d',$date[2]),date('d',$date[3]),date('d',$date[4])).'<br>';
echo 'Наибольший месяц: '.max(date('m',$date[0]),date('m',$date[1]),date('m',$date[2]),date('m',$date[3]),date('m',$date[4])).'<br>';

// Сортируем массив по возрастанию дат
sort($date);

/*
// Вывод массива
echo date('Y.m.d', $date[0]).'<br>'
    .date('Y.m.d', $date[1]).'<br>'
    .date('Y.m.d', $date[2]).'<br>'
    .date('Y.m.d', $date[3]).'<br>'
    .date('Y.m.d', $date[4]).'<br>';
*/

// Извлекаем послений элемент массива
$selected = array_pop($date);

// Вывод переменной $selected в формате "дд.мм.ГГ ЧЧ:ММ:СС"
echo '<br>Переменная $selected = '.date('d.m.Y h:i:s',$selected).'<br><br>';

// Смена текущего часового пояса
echo date_default_timezone_get().'<br>';
date_default_timezone_set('America/New_York');
echo date_default_timezone_get().'<br><br>';

?>