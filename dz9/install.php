<?php

require_once './config.php';

if (isset($_POST['install'])) {

    $err = '';

    foreach ($_POST as $form_key => $form_value) {
        if (!empty($form_value)) {
            $$form_key = trim($form_value);
            $smarty->assign("$form_key", $$form_key);
        } else {
            $err = 'Все поля обязательны для заполнения<br>';
        }
    }

    $smarty->assign('err', $err);

    if (!empty($err)) {
        header('Location : install.php');
    } else {

        if ($conn = mysql_connect($server_name, $user_name, $password)) {

            $damp = file_get_contents('ads_db.sql');
            $damp = preg_replace('/ads_db/', $database, $damp);

            $query_mas = explode(';', $damp);

            foreach ($query_mas as $query) {
                if (!mysql_query(trim($query))) {
                    $err .= "Ошибка при выполнении запроса: " . mysql_error() . "<br>";
                    break;
                }
            }

            if (empty($err)) {
                $success = "Инсталяция прошла успешно!<br><a href='index.php'>На главную</a>";
            }
            
        } else {
            $err .= "Невозможно установить соединение: " . mysql_error() . "<br>";
        }
        $smarty->assign('err', $err);
    }
}

// -------------------------------------------------------

if (isset($success) && !empty($success)) {
    $filename = 'db.php';
    if (!$handle = fopen($filename, 'w')) { 
        echo "Не могу открыть файл ".$filename; 
        exit; 
    } 
    // считываем всё содержимое файла 
    $content = '<?php'."\n"
            . '$server_name = \''.$server_name.'\';'."\n"
            .'$user_name = \''.$user_name.'\';'."\n"
            .'$password = \''.$password.'\';'."\n"
            .'$database = \''.$database.'\';'."\n";
            
    fwrite($handle, $content);
    fclose($handle);
  
    $smarty->assign('success', $success);
    $smarty->display('install_success.tpl');
} else {
    $smarty->display('install.tpl');
}