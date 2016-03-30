<?php

function show_ads(){
    $args = func_get_args();
    $mas = isset($args[0]) ? $args[0] : '';
    ?>
    <form  method="post", align="center">
    <div>
    <?php           
        if (isset($mas['private']) && ($mas['private']==1)) {
    ?>
        <input type="radio" value="0" name="private" >Частное лицо 
        <input type="radio" value="1" name="private" checked="">Компания <br><br>  
        </div>
    <?php
        } else {
    ?>
        <input type="radio" value="0" name="private" checked="">Частное лицо 
        <input type="radio" value="1" name="private" >Компания <br><br>
        </div>
    <?php    
        }
    ?>
    <div><b>Ваше имя: </b><input type="text" value="<?= isset($mas['seller_name']) ? $mas['seller_name'] : ''; ?>" name="seller_name" maxlength="40"><br><br></div>
    <div>Электронная почта: <input type="text" value="<?= isset($mas['email']) ? $mas['email'] : ''; ?>" name="email"><br><br></div>
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
    <div>Номер телефона <input type="text" value="<?= isset($mas['phone']) ? $mas['phone'] : ''; ?>" name="phone"><br><br></div>
    <div>Город 
        <select title="Выберите Ваш город" name="location_id"> 
            <option value="">-- Выберите город --</option>
            <option disabled="disabled">-- Города --</option>
            <?php
            $citys = array('641780' => 'Новосибирск','641490' => 'Барабинск','641510' => 'Бердск','641600' => 'Искитим', 
                            '641630' => 'Колывань','641680' => 'Краснообск','641710' => 'Куйбышев','641760' => 'Мошково');
            foreach ($citys as $number => $city) {
                $location_id = isset($mas['location_id']) ? $mas['location_id'] : ''; 
                $check = ($number == $location_id) ? 'selected=""' : '';
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
                $category_id = isset($mas['category_id'])? $mas['category_id'] : '';
                $check = (($category_id != null) && ($key == $category_id)) ? 'selected=""' : '';
                echo '<option value="'.$key.'" '.$check.'>'.$volume.'</option>';  
            }
            ?>
        </select><br><br>
    </div>
    <div>Название объявления <input type="text" value="<?= isset($mas['title']) ? $mas['title'] : ''; ?>" name="title" maxlength="50"><br><br></div>
    <div>Описание объявления <textarea name="description" maxlength="3000"><?= isset($mas['description']) ? $mas['description'] : ''; ?></textarea><br><br></div>
    <div>Цена <input type="text" value="<?= isset($mas['price']) ? $mas['price'] : ''; ?>" name="price" maxlength="9">&nbsp;<span>руб.</span><br><br></div>
    <div><input type="submit" value="Далее" name="main_form_submit"><br><br></div>
    </form>
<?php
}

function print_ads() {
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
}

function delete_ads() {
    if (isset($_GET['delete'])) {
        if ($_GET['delete'] == 0) {
            unset($_SESSION['ads']);
            header("Location: index.php");
        } else {
            unset($_SESSION['ads'][$_GET['delete']]);
            header("Location: index.php");
        }
    }
}

function add_ad() {
    if (isset($_POST['main_form_submit'])) {
        $_SESSION['ads'][uniqid()] = $_POST;
        unset($_POST);
        header("Location: index.php");
    }
}
