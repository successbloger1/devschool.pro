<?php

function db_connect($server_name, $user_name, $password, $database) {
    global $mysqli;
    
    $mysqli = new mysqli($server_name, $user_name, $password, $database);
    if ($mysqli->connect_errno) {
        echo 'Не удалось подключиться к MySQL: ' . $mysqli->connect_error;
        exit;
    }
    if (!$mysqli->set_charset("utf8")){
        echo 'Не удалось установить кодировку: ' . $mysqli->error;
        exit;
    }

}

function db_disconnect() {
    global $mysqli;
    
    $mysqli->close();
}

// Доработать
function form_construct($query) {
    global $mysqli;
    $data_query = $mysqli->query($query);
    if ($mysqli->errno) {
        echo 'Невозможно выполнить запрос. Ошибка: ' . $mysqli->connect_error;
        exit;
    }
    $data = array();
    while ($data_temp = $data_query->fetch_assoc()) {
        $data += array($data_temp['id'] => $data_temp['name']);
    }
    $data_query->free();
    
    return $data;
    
}

function form_data() {
    global $mysqli;
    
    $args = func_get_args();

    if (isset($args[0])) {
        
        if (is_array($args[0])) {
            $mas = $args[0];
            $err = array('err' => $args[1]);
            $mas = array_merge($mas, $err);
        } else {
            $id = $args[0];
            $query = "SELECT * FROM ads WHERE id = '$id'";
            $ad_query = $mysqli->query($query);
            if ($mysqli->errno) {
                echo 'Невозможно выполнить запрос. Ошибка: ' . $mysqli->connect_error;
                exit;
            }
            $mas = $ad_query->fetch_assoc();
            array_merge($mas, array('err' => ''));
        }
        
    } else {
        $query = "SHOW COLUMNS FROM ads";
        $result = $mysqli->query($query);
        if ($mysqli->errno) {
            echo 'Невозможно выполнить запрос. Ошибка: ' . $mysqli->connect_error;
            exit;
        }
        $mas = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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

    return $query;
}

function print_ads() {
    global $mysqli;
    
    $args = func_get_args();
    if (isset($args[0])) {
        
        $sort = $mysqli->real_escape_string($args[0]);
        $query = sort_ads($sort);
        
    } else {
        $query = "SELECT * FROM ads";
    }

    $ads_query = $mysqli->query($query);
    if ($mysqli->errno) {
        echo 'Невозможно выполнить запрос. Ошибка: ' . $mysqli->connect_error;
        exit;
    }

    if ($ads_query->num_rows != 0) {

            while ($ads_temp = $ads_query->fetch_assoc()) {
                $ads[] = $ads_temp;
            }

            $ads_query->free();
            
        } else {
            $ads = '';
        }
    
    return $ads;
}

function delete_ads() {
    global $mysqli;
    
    if ($_GET['delete'] == '0') {
        $query = "TRUNCATE TABLE ads";
    } else {
        $delete = $mysqli->real_escape_string($_GET['delete']);
        $query = "DELETE FROM ads WHERE id = '$delete'";
    }
    $mysqli->query($query);
    if ($mysqli->errno) {
        echo 'Невозможно выполнить запрос. Ошибка: ' . $mysqli->connect_error;
        exit;
    }
    
    header('Location: ' . SCRIPT_NAME);
}

function save_ad($data) {
    global $mysqli;
    
    $id = $mysqli->real_escape_string($_GET['id']);
    $into_query = '';
    
    foreach ($data as $key => $value) {
        $into_query .= "$key = '$value', ";
    }

    $query = "UPDATE ads SET ".rtrim($into_query, ', ')." WHERE id = '$id'";
    $mysqli->query($query);
    if ($mysqli->errno) {
        echo 'Невозможно выполнить запрос. Ошибка: ' . $mysqli->connect_error;
        exit;
    }
}

function add_ad($data) {
    global $mysqli;
    
    if (isset($_POST['create'])) {

        $array_val = array_merge(array('id' => uniqid()), $data);
        $query = "INSERT INTO ads VALUES (" . "'" . implode('\', \'', $array_val) . "'" . ")";

        $mysqli->query($query);
        if ($mysqli->errno) {
            echo 'Невозможно выполнить запрос. Ошибка: ' . $mysqli->connect_error;
            exit;
        }
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
