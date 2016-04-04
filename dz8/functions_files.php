<?php

function show_ads() {
    $args = func_get_args();

    if (isset($args[0])) {
        if (is_array($args[0])) {
            $mas = $args[0];
            $err = array('err' => $args[1]);
            $mas = array_merge($mas,$err);
        } else {

            $id = $args[0];

            if (file_exists(FILE_NAME) && filesize(FILE_NAME)) {
                $file = file(FILE_NAME);
                $mas = '';

                for ($i = 0; $i < sizeof($file); $i++) {
                    $ads = unserialize($file[$i]);
                    foreach ($ads as $key => $value) {
                        if ($key == $id) {
                            $mas = $value;
                            array_merge($mas, array('err' => ''));
                        }
                    }
                }
            }
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
    if (file_exists(FILE_NAME) && filesize(FILE_NAME)) {

        if (!$handle = fopen(FILE_NAME, 'r')) {
            echo 'Не могу открыть файл ' . FILE_NAME;
            exit;
        }

        while (!feof($handle)) {
            $buffer = fgets($handle);
            if ($buffer) {
                $ads[] = unserialize($buffer);
            }
        }

        fclose($handle);
        $i = 0;

  } else {
      $ads = '';
  }
  return $ads;
}

function delete_ads() {
    if ($_GET['delete'] == '0') {
        unlink(FILE_NAME);
    } else {
        $file = file(FILE_NAME);

        for ($i = 0; $i < sizeof($file); $i++) {
            $ads = unserialize($file[$i]);
            foreach ($ads as $key => $value) {
                if ($key == $_GET['delete']) {
                    unset($file[$i]);
                }
            }
        }

        file_put_contents (FILE_NAME, $file);
    }
    header('Location: ' . SCRIPT_NAME);
}

function save_ad($data) {

    $file = file(FILE_NAME);
    $id = $_GET['id'];
    
    for ($i = 0; $i < sizeof($file); $i++) {
        $ads = unserialize($file[$i]);
        foreach ($ads as $key => $value) {
            if ($key == $id) {
                $ads[$id] = $data;
                $file[$i] = serialize($ads)."\n";
            }
        }
    }

    file_put_contents (FILE_NAME, $file);

}

function add_ad($data) {

    if (isset($_POST['create'])) {

        $ad[uniqid()] = $data;

        $content = serialize($ad);
        $content .= "\n";

        file_put_contents (FILE_NAME, $content, FILE_APPEND);
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
