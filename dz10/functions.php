<?php

function form_construct($name) {
    global $db;
    
    return $db->selectCol('SELECT id AS ARRAY_KEY, name FROM '.$name);
}

function form_data() {
    global $db;

    $args = func_get_args();

    if (isset($args[0])) {
        if (is_array($args[0])) {
            $err = array('err' => $args[1]);
            $mas = array_merge($args[0], $err);
        } else {
            $id = $args[0];
            $result = $db->select("SELECT * FROM ads WHERE id = ?", $id);
            $mas = $result[0];
        }
    } else {
        $result = $db->query('SHOW COLUMNS FROM ads');
        $mas = array();
        foreach ($result as $row) {
            $mas += array($row['Field'] => $row['Default']);
        }
    }

    return $mas;
}

function sort_ads ($sort) {
    global $db;
    
    $order = isset($_COOKIE['order']) ? $_COOKIE['order'] : '';
    
    $allowed = array('title', 'price'); //перечисляем варианты
    $key = array_search($sort, $allowed); // ищем среди них переданный параметр
    $orderby = $allowed[$key]; //выбираем найденный (или, за счёт приведения типов - первый) элемент. 
    $order = ($order == 'DESC') ? 'ASC' : 'DESC'; // определяем направление сортировки

    setcookie('order', $order);

    return $db->query("SELECT * FROM ads ORDER BY $orderby $order");
}

function print_ads() {
    global $db;
    
    $args = func_get_args();
    
    if (isset($args[0])) {
        
        $ads = sort_ads($args[0]);
    } else {
        $ads = $db->query('SELECT * FROM ads');
    }

    return !empty($ads) ? $ads : '';
}

function delete_ads() {
    global $db;
    
    if ($_GET['delete'] == '0') {
        $db->query('TRUNCATE TABLE ads');
    } else {
        $db->query('DELETE FROM ads WHERE id = ?', $_GET['delete']);
    }

    header('Location: ' . SCRIPT_NAME);
}


function save_ad($ad) {
    global $db;
    
    $db->query('UPDATE ads SET ?a WHERE id = ?', $ad, $_GET['id']); 
}


function add_ad($data) {
    global $db;
    
    if (isset($_POST['create'])) {
        
        $ad = array_merge(array('id' => uniqid()), $data);
        $db->query('INSERT INTO ads (?#) VALUES (?a)', array_keys($ad), array_values($ad));

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
