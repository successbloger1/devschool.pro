<?php

function db_connect($server_name, $user_name, $password, $database) {
    mysql_connect($server_name, $user_name, $password) or die("Невозможно установить соединение: ". mysql_error());
    mysql_select_db ($database) or die("Невозможно выбрать базу данных: ". mysql_error());
    mysql_set_charset('utf8') or die("Невозможно сменить кодировку: ". mysql_error());
}

// Доработать
function form_construct($query) {

    $data_query = mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());
    $data = array();
    while ($data_temp = mysql_fetch_assoc($data_query)) {
        $data += array($data_temp['id'] => $data_temp['name']);
    }
    mysql_free_result($data_query);
    
    return $data;
    
}

function form_data() {
    $args = func_get_args();

    if (isset($args[0])) {
        
        if (is_array($args[0])) {
            $mas = $args[0];
            $err = array('err' => $args[1]);
            $mas = array_merge($mas, $err);
        } else {
            $id = $args[0];
            $query = "SELECT * FROM ads WHERE id = '$id'";
            $ad_query = mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());
            $mas = mysql_fetch_assoc($ad_query);
            array_merge($mas, array('err' => ''));
        }
        
    } else {
        $query = "SHOW COLUMNS FROM ads";
        $result = mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());
        $mas = array();
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $mas += array($row['Field'] => $row['Default']);
            }
        }
    }

    return $mas;
}

function sort_ads ($sort) {
    
    $i = isset($_COOKIE['i']) ? (int)$_COOKIE['i'] : 0;
    $type = ($i%2) ? 'DESC' : 'ASC';
    $query = "SELECT * FROM ads ORDER BY $sort $type";
    setcookie('i', ++$i);

    var_dump($i);
    
    return $query;
}

function print_ads() {
    $args = func_get_args();
    if (isset($args[0])) {
        
        $sort = mysql_real_escape_string($args[0]);
        $query = sort_ads($sort);
        
    } else {
        $query = "SELECT * FROM ads";
    }

    $ads_query = mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());

        if (mysql_num_rows($ads_query) != 0) {

            while ($ads_temp = mysql_fetch_assoc($ads_query)) {
                $ads[] = $ads_temp;
            }

            mysql_free_result($ads_query);
        } else {
            $ads = '';
        }
    
    return $ads;
}

function delete_ads() {
    if ($_GET['delete'] == '0') {
        $query = "TRUNCATE TABLE ads";
    } else {
        $delete = mysql_real_escape_string($_GET['delete']);
        $query = "DELETE FROM ads WHERE id = '$delete'";
    }
    mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());
    
    header('Location: ' . SCRIPT_NAME);
}

function save_ad($data) {

    $id = mysql_real_escape_string($_GET['id']);
    $into_query = '';
    
    foreach ($data as $key => $value) {
        $into_query .= "$key = '$value', ";
    }

    $query = "UPDATE ads SET ".rtrim($into_query, ', ')." WHERE id = '$id'";
    mysql_query($query) or die("Невозможно выполнить запрос <$query>. Ошибка: " . mysql_error());
}

function add_ad($data) {

    if (isset($_POST['create'])) {

            $array_val = array_merge(array('id' => uniqid()), $data);
            $query = "INSERT INTO ads VALUES ("."'".implode('\', \'', $array_val)."'".")";

            mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: '. mysql_error());
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
