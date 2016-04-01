<?php

function show_ads() {
    $args = func_get_args();

    if (isset($args[0])) {
        if (is_array($args[0])) {
            $mas = $args[0];
            $err = $args[1];
        } else {

            $id = $args[0];

            if (isset($_COOKIE['ads'])) {
                $cookie = unserialize($_COOKIE['ads']);
                $mas = '';

                foreach ($cookie as $key => $value) {
                    if ($key == $id) {
                        $mas = $value;
                    }
                }
            }
        }
    } else {
        $mas = '';
    }

    require_once('form.php');
}

function print_ads() {
    if (isset($_COOKIE['ads'])) {

        $ads = unserialize($_COOKIE['ads']);
        $i = 0;

        foreach ($ads as $id => $ad) {
            echo '<center>'.++$i.' | <a href="?id=' . $id . '">' . $ad['title']
            . '</a>  | ' . $ad['price'] . ' руб. | ' . $ad['seller_name']
            . ' | <a href="?delete=' . $id . '">Удалить</a>';
        }
        echo '<center><a href="?delete=0"><br>Удалить все объявления</a></center><br>';
    } else {
        echo '<center>Объявлений нет</center>';
    }
}

function delete_ads() {
        if ($_GET['delete'] == 0) {
            setcookie('ads', 0, time() - 1000);
        } else {
            $ads = unserialize($_COOKIE['ads']);
            unset($ads[$_GET['delete']]);
            if (!empty($ads)) {
                setcookie('ads', serialize($ads), time()+3600*24*7);
            } else {
                setcookie('ads', 0, time() - 1000);
            }
        }
        header('Location: '.SCRIPT_NAME);
}

function save_ad($data) {
    $ads = unserialize($_COOKIE['ads']);
    $id = $_GET['id'];
    
    $ads[$id] = $data;
    setcookie('ads', serialize($ads), time()+3600*24*7);
}

function add_ad($data) {

    if (isset($_POST['create'])) {
        if (isset($_COOKIE['ads'])) {
            $ads = unserialize($_COOKIE['ads']);
        } else {
            $ads = [];
        }
        $ads[uniqid()] = $data;
        setcookie('ads', serialize($ads), time() + 3600 * 24 * 7);
    }
    
    if (isset($_POST['save'])) {
        save_ad($data);
    }

    unset($_POST);
    header('Location: ' . SCRIPT_NAME);
}

function validation($form_data){
    
    $err = '';
    
    foreach ($form_data as $key => $value) {
        $value = trim($value); // Убираем пробелы по краям
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value); //Убираем слеши, если надо  
        }
        $value = htmlspecialchars($value, ENT_QUOTES); //Заменяем служебные символы HTML на эквиваленты  
        $form_data[$key] = $value;
    }

    $private = (int) $form_data['private'];

    $seller_name = $form_data['seller_name'];
    if (empty($seller_name)) {
        $err .= 'Поле "Имя" обязательно для заполнения<br/>';
    }

    $email = $form_data['email'];
    if (!preg_match('/^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,4}$/i', $email) && !empty($email)) {
        $err .= 'Неверный Email<br/>';
    }

    $allow_mails = isset($form_data['allow_mails']) ? $form_data['allow_mails'] : '';

    $phone = $form_data['phone'];
    if (!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $phone) && !empty($phone)) {
        $err .= 'Неверный телефонный номер<br/>';
    }

    $location_id = (int) $form_data['location_id'];
    $category_id = (int) $form_data['category_id'];

    $title = $form_data['title'];
    if (empty($title)) {
        $err .= 'Поле "Название" обязательно для заполнения<br/>';
    }
    
    $description = $form_data['description'];

    $price = $form_data['price'];
    if (empty($price)) {
        $err .= 'Поле "Цена" обязательно для заполнения<br/>';
    }
    if (!preg_match('/^(?:\d+|\d*\.\d+)$/', $price) && !empty($price)) {
        $err .= 'Неверно указана цена<br/>';
    }

    return $valid = [ 
        'err' => $err,
        'valid' => [
            'private' => $private,
            'seller_name' => $seller_name,
            'email' => $email,
            'allow_mails' => $allow_mails,
            'phone' => $phone,
            'location_id' => $location_id,
            'category_id' => $category_id,
            'title' => $title,
            'description' => $description,
            'price' => $price,
        ]
    ];
}

    ?>
