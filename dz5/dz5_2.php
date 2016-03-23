<?php

$news='Четыре новосибирские компании вошли в сотню лучших работодателей
Выставка университетов США: открой новые горизонты
Оценку «неудовлетворительно» по качеству получает каждая 5-я квартира в новостройке
Студент-изобретатель раскрыл запутанное преступление
Хоккей: «Сибирь» выстояла против «Ак Барса» в пятом матче плей-офф
Здоровое питание: вегетарианская кулинария
День святого Патрика: угощения, пивной теннис и уличные гуляния с огнем
«Красный факел» пустит публику на ночные экскурсии за кулисы и по закоулкам столетнего здания
Звезды телешоу «Голос» Наргиз Закирова и Гела Гуралиа споют в «Маяковском»';
$news=  explode("\n", $news);

function print_all($news_in) {
    for ($i=0; $i<count($news_in); $i++) {
        echo $i.'. '.$news_in[$i].'<br>';
    }
}

function print_some_news ($news_in, $id_in) {
        echo $id_in.'. '.$news_in[$id_in].'<br>';
}

if (isset($_POST['id'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); 
    
    if (($id > count($news)) || ($id < 0) || ($id === FALSE)) {
        echo 'Новости с ID - "' . $_POST['id'] . '" нет. Весь список новостей:<br>';
        print_all($news);
    } else {
        echo 'Новость с ID - "' . $id . '": <br>';
        print_some_news($news, $id);
    }
    
} else {
    header("HTTP/1.0 404 Not Found");
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>FORM</title>
    </head>
    <body>

        <form method="POST">
            <p><b>Укажите ID новости:</b></p>
            <p><input type="text" name="id"></p>
            <p><input type="submit"></p>
        </form>

    </body>
</html>