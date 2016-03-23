<?php

// Печать формы объявления
function show_ads(){
    ?>
    <form  method="post", align="center">
    <div>
        <input type="radio" value="1" name="private" checked="">Частное лицо 
        <input type="radio" value="0" name="private">Компания <br><br>
    </div>
    <div><b>Ваше имя: </b><input type="text" value="" name="seller_name" maxlength="40"><br><br></div>
    <div>Электронная почта: <input type="text" value="" name="email"><br><br></div>
    <div> 
        <input type="checkbox" value="1" name="allow_mails">
        <span>Я не хочу получать вопросы по объявлению по e-mail</span><br><br>
    </div>
    <div>Номер телефона <input type="text" value="" name="phone"><br><br></div>
    <div>Город 
        <select title="Выберите Ваш город" name="location_id"> 
            <option value="">-- Выберите город --</option>
            <option disabled="disabled">-- Города --</option>
            <?php
            $citys = array('641780' => 'Новосибирск','641490' => 'Барабинск','641510' => 'Бердск','641600' => 'Искитим', 
                            '641630' => 'Колывань','641680' => 'Краснообск','641710' => 'Куйбышев','641760' => 'Мошково');
            foreach ($citys as $number => $city) {
                echo '<option value="'.$number.'">'.$city.'</option>';  
            }
            ?>
        </select><br><br>
    </div>
    <div>Категория 
        <select title="Выберите категорию объявления" name="category_id"> 
            <option value="">-- Выберите категорию --</option>
            <?php
            $category = array('Транспорт','Недвижимость','Работа','Услуги','Личные вещи','Для дома и дачи','Бытовая электроника','Прочее');
            foreach ($category as $key => $volume) {
                echo '<option value="'.$key.'">'.$volume.'</option>';  
            }
            ?>
        </select><br><br>
    </div>
    <div>Название объявления <input type="text" value="" name="title" maxlength="50"><br><br></div>
    <div>Описание объявления <textarea name="description" maxlength="3000"></textarea><br><br></div>
    <div>Цена <input type="text" value="0" name="price" maxlength="9">&nbsp;<span>руб.</span><br><br></div>
    <div><input type="submit" value="Далее" name="main_form_submit"><br><br></div>
    </form>
<?php
}

// Печать формы выбранного объявления
function show_my_ad($mas){
    ?>
    <form  method="post", align="center">
    <div>
    <?php           
        if ($mas['private']==1) {
    ?>
        <input type="radio" value="1" name="private" checked="">Частное лицо 
        <input type="radio" value="0" name="private">Компания <br><br>  
        </div>
    <?php
        } else {
    ?>
        <input type="radio" value="1" name="private" >Частное лицо 
        <input type="radio" value="0" name="private" checked="">Компания <br><br>
        </div>
    <?php    
        }
    ?>
    <div><b>Ваше имя: </b><input type="text" <?php echo 'value="'.$mas['seller_name'].'" '; ?> name="seller_name" maxlength="40"><br><br></div>
    <div>Электронная почта: <input type="text" <?php echo 'value="'.$mas['email'].'" '; ?> name="email"><br><br></div>
    <div> 
        <?php           
        if (isset($mas['allow_mails'])) {
        ?>
        <input type="checkbox" checked="" name="allow_mails">
        <?php    
        } else {
        ?>
        <input type="checkbox" name="allow_mails">
        <?php    
        }
        ?>
        <span>Я не хочу получать вопросы по объявлению по e-mail</span><br><br>
    </div>
    <div>Номер телефона <input type="text" <?php echo 'value="'.$mas['phone'].'" '; ?> name="phone"><br><br></div>
    <div>Город 
        <select title="Выберите Ваш город" name="location_id"> 
            <option value="">-- Выберите город --</option>
            <option disabled="disabled">-- Города --</option>
            <?php
            $citys = array('641780' => 'Новосибирск','641490' => 'Барабинск','641510' => 'Бердск','641600' => 'Искитим', 
                            '641630' => 'Колывань','641680' => 'Краснообск','641710' => 'Куйбышев','641760' => 'Мошково');
            foreach ($citys as $number => $city) {
                $check = ($number==$mas['location_id']) ? 'selected=""' : '';
                echo '<option value="'.$number.'" '.$check.'>'.$city.'</option>';
            }
            ?>
        </select><br><br>
    </div>
    <div>Категория 
        <select title="Выберите категорию объявления" name="category_id"> 
            <option value="">-- Выберите категорию --</option>
            <?php
            $category = array('Транспорт','Недвижимость','Работа','Услуги','Личные вещи','Для дома и дачи','Бытовая электроника','Прочее');
            foreach ($category as $key => $volume) {
                $check = ($key==$mas['category_id']) ? 'selected=""' : '';
                echo '<option value="'.$key.'" '.$key.'>'.$volume.'</option>';  
            }
            ?>
        </select><br><br>
    </div>
    <div>Название объявления <input type="text" <?php echo 'value="'.$mas['title'].'" '; ?> name="title" maxlength="50"><br><br></div>
    <div>Описание объявления <textarea name="description" maxlength="3000"><?php echo $mas['description']; ?></textarea><br><br></div>
    <div>Цена <input type="text" <?php echo 'value="'.$mas['price'].'" '; ?> name="price" maxlength="9">&nbsp;<span>руб.</span><br><br></div>
    <div><input type="submit" value="Далее" name="main_form_submit"><br><br></div>
    </form>
<?php
}

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
    show_my_ad($_SESSION['ads'][$_GET['id']]);
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

