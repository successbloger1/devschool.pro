<?php

function db_connect() {
    $conn = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Невозможно установить соединение: ". mysql_error());
    mysql_select_db (DB_NAME, $conn) or die("Невозможно выбрать базу данных: ". mysql_error());
    mysql_set_charset('utf8') or die("Невозможно сменить кодировку: ". mysql_error());
}

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
            $query = "SELECT id, private, seller_name, email, allow_mails, phone, location_id, category_id, title, description, price "
                    . "FROM ads "
                    . "WHERE id = '$id'";
            $ad_query = mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());
            $mas = mysql_fetch_assoc($ad_query);
            array_merge($mas, array('err' => ''));
        }
        
    } else {
        $mas = array(
            'private' => 0,
            'seller_name' => '',
            'email' => '',
            'allow_mails' => '',
            'phone' => '',
            'location_id' => 0,
            'category_id' => 0,
            'title' => '',
            'description' => '',
            'price' => '',
            'err' => ''
        );
    }

    return $mas;
}

function print_ads() {
    
    $query = "SELECT id, private, seller_name, email, allow_mails, phone, location_id, category_id, title, description, price FROM ads";
    
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
        mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());
    } else {
        $delete = mysql_real_escape_string($_GET['delete']);
        
        $query = "DELETE FROM ads WHERE id = '$delete'";
        mysql_query($query) or die('Невозможно выполнить запрос. Ошибка: ' . mysql_error());
    }
    header('Location: ' . SCRIPT_NAME);
}

function save_ad($data) {

    $id = mysql_real_escape_string($_GET['id']);
    
    $query = "UPDATE ads "
            . "SET private = $data[private], seller_name = '$data[seller_name]', "
            . "email = '$data[email]', allow_mails = '$data[allow_mails]', phone = '$data[phone]', "
            . "location_id = $data[location_id], category_id = $data[category_id], "
            . "title = '$data[title]', description = '$data[description]', price = $data[price] "
            . "WHERE id = '$id'";
    mysql_query($query) or die("Невозможно выполнить запрос <$query>. Ошибка: " . mysql_error());
}

function add_ad($data) {

    if (isset($_POST['create'])) {

            $array_val = array_merge(array('id' => uniqid()), $data);
            $query = "INSERT INTO ads (id, private, seller_name, email, allow_mails, phone, location_id, category_id, title, description, price) "
                    . "VALUES ("."'".implode('\', \'', $array_val)."'".")";

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
