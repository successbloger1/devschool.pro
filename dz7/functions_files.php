<?php

define('FILE_NAME', 'ads.txt');

function show_ads(){
    $args = func_get_args();
    
    if (isset($args[0])) {
        $id = $args[0];
        if (file_exists(FILE_NAME) && filesize(FILE_NAME)) {
            $file = file(FILE_NAME);
            $mas = '';
            
            for ($i = 0; $i < sizeof($file); $i++) {
                $ads = unserialize($file[$i]);
                foreach ($ads as $key => $value) {
                    if ($key == $id) {
                        $mas = $value;
                    } 
                }
            }
        }
    } else {
        $mas = '';
    }
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
    if (file_exists(FILE_NAME) && filesize(FILE_NAME)) {

        if (!$handle = fopen(FILE_NAME, 'r')) {
            echo 'Не могу открыть файл '. FILE_NAME;
            exit;
        }
        
        while (!feof($handle)) {
            $buffer = fgets($handle);
            if($buffer) {
                $ads[] = unserialize($buffer);
            } 
        }

        fclose($handle);

        foreach ($ads as $mas) {
            foreach ($mas as $id => $ad) {
            echo '<ul><center><li><a href="?id=' . $id . '">' . $ad['title']
            . '</a>  | ' . $ad['price'] . ' руб. | ' . $ad['seller_name']
            . ' | <a href="?delete=' . $id . '">Удалить</a></li></center></ul>';
        }
        }
        print ('<a href="?delete=0"><center><br>Удалить все объявления</center></a><br>');
    } else {
        echo '<center>Объявлений нет</center>';
    }
}

function delete_ads() {
    if (isset($_GET['delete'])) {
        if ($_GET['delete'] == '0') {
            unlink(FILE_NAME);
            header("Location: dz7_2.php");
        } else {
            $file = file(FILE_NAME);
            
            for($i=0; $i<sizeof($file); $i++) {
                $ads = unserialize($file[$i]);
                if (key($ads) == $_GET['delete']){
                    unset ($file[$i]);
                }
            }

            if (!$handle = fopen(FILE_NAME, 'w')) {
                echo 'Не могу открыть файл ' . FILE_NAME;
                exit;
            }
            
            fputs($handle, implode('', $file));
            fclose($handle);
            header("Location: dz7_2.php");
        }
    }
}

function add_ad() {
    if (isset($_POST['main_form_submit'])) {

        if (!$handle = fopen(FILE_NAME, 'a')) {
            echo 'Не могу открыть файл '. FILE_NAME;
            exit;
        }
        
        $ad[uniqid()] = $_POST;

        $content = serialize($ad);
        $content .= "\n";

        if (fwrite($handle, $content) === FALSE) {
            echo 'Не могу сохранить в файл '. FILE_NAME;
            exit;
        }

        fclose($handle);
        
        unset($_POST);
        header("Location: dz7_2.php");
    }
}

    ?>
