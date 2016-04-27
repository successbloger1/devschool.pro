<?php

require_once './config.php';

if (isset($_POST['install'])) {

    $err = '';

    foreach ($_POST as $form_key => $form_value) {
        $$form_key = trim($form_value);
        $smarty->assign("$form_key", $$form_key);
    }

    $mysqli = new mysqli($server_name, $user_name, $password);
    if (!$mysqli->connect_errno) {

        $mysqli->select_db($database);
        if ($mysqli->errno) {
            if ($mysqli->errno == 1049) {
                $sql = "CREATE DATABASE $database";
                $mysqli->query($sql);
                if ($mysqli->errno) {
                    $err .= 'Ошибка при создании базы данных: ' . $mysqli->error . "<br>";
                }
                $mysqli->select_db($database);
                if ($mysqli->errno) {
                    $err .= 'Ошибка при выборе базы данных: ' . $mysqli->error . "<br>";
                }
            } else {
                $err .= 'Ошибка при выборе базы данных: ' . $mysqli->error . "<br>";
            }
        }

        $damp = file_get_contents('ads_db.sql');
        $query_mas = explode(';', $damp);

        foreach ($query_mas as $query) {
            $mysqli->query(trim($query));
            if ($mysqli->errno) {
                $err .= 'Ошибка при выполнении запроса: ' . $mysqli->error . "<br>";
                break;
            }
        }

        $mysqli->close();
        
    } else {
        $err .= "Не удалось подключиться к MySQL: " . $mysqli->connect_error . "<br>";
    }

    if (empty($err)) {
        $success = "Инсталяция прошла успешно!<br><a href='index.php'>На главную</a>";
    }

    $smarty->assign('err', $err);
}

// -------------------------------------------------------

if (isset($success)) {
    
    // -- сохраняем данные в файл
    $filename = 'db.php';
    if (!$handle = fopen($filename, 'w')) { 
        echo "Не могу открыть файл ".$filename; 
        exit; 
    } 
    
    $content = '<?php'."\n"
            .'$server_name = \''.$server_name.'\';'."\n"
            .'$user_name = \''.$user_name.'\';'."\n"
            .'$password = \''.$password.'\';'."\n"
            .'$database = \''.$database.'\';'."\n";
            
    fwrite($handle, $content);
    fclose($handle);
    // --
    
    $smarty->assign('success', $success);
    $smarty->display('install_success.tpl');
} else {
    $smarty->display('install.tpl');
}