<?php

// ---------- Configuration Smarty ----------

require_once './smarty/libs/Smarty.class.php';

$smarty = new Smarty();

$smarty->compile_check = true;
//$smarty->debugging = true;

$smarty->template_dir = './smarty/templates/';
$smarty->compile_dir = './smarty/templates_c/';
$smarty->cache_dir = './smarty/cache/';
$smarty->config_dir = './smarty/configs/';

// ----------

//var_dump($_SERVER); exit;

require_once 'Vk.php';

$write = false;

if (file_exists('config.php') && filesize('config.php') > 0) {

    include_once 'config.php';
    $smarty->assign('app_id', $app_id);
    $smarty->assign('secret', $secret);
    $smarty->assign('group_id', $group_id);
    $smarty->assign('xml', $xml);
    $smarty->assign('load_available', $load_available);
    $smarty->assign('resize_photo', $resize_photo);
    $smarty->assign('loging', $loging);

    if (isset($_GET['loguot']) || (empty($token))) {
        $token = '';

        $config = array(
            'app_id' => $app_id,
            'secret' => $secret,
            'scopes' => explode(',', $scopes),
            'redirect_uri' => $redirect_uri
        );

        $vk = new Vk($config);

        if (!isset($_GET['code'])) {
            $link = '<br><br>Пройдите аутентификацию, для это кликните <a href=' . $vk->getLoginUrl() . '>сюда</a>';
        } else {
            $token = $vk->authenticate()->getAccessToken();
            $user_id = $vk->getUserId();
            $user = $vk->api('users.get', array('user_ids' => $user_id));
            $link = '<br><br><p>Вы вошли под именем ' . $user[0]->first_name . ' ' . $user[0]->last_name . ' <a href="?loguot">Выйти</a></p>';
        }
        
        $write = true;
        
    } else {
        $vk = new Vk(['access_token' => $token]);

        $user = $vk->api('users.get', array('user_ids' => $user_id));
        $link = '<br><br><p>Вы вошли под именем ' . $user[0]->first_name . ' ' . $user[0]->last_name . '. <a href="?loguot">Выйти</a></p>';
    }

    $smarty->assign('link', $link);

}

if (isset($_POST['install'])) {

    $err = '';

    if (empty($_POST['app_id']))
        $err .= 'Введите Application ID <br/>';
    else
        $app_id = $_POST['app_id'];
    if (empty($_POST['secret']))
        $err .= 'Введите Application Secret <br/>';
    else
        $secret = $_POST['secret'];
    if (empty($_POST['group_id']))
        $err .= 'Введите Group ID <br/>';
    else
        $group_id = $_POST['group_id'];
    if (empty($_POST['xml']))
        $err .= 'Введите XML file link <br/>';
    else
        $xml = $_POST['xml'];
    if (empty($_POST['load_available']))
        $load_available = '';
    else
        $load_available = '1';
    if (empty($_POST['resize_photo']))
        $resize_photo = '';
    else
        $resize_photo = '1';
    if (empty($_POST['loging']))
        $loging = '';
    else
        $loging = '1';

    if (empty($err)) {
        $smarty->assign('app_id', $app_id);
        $smarty->assign('secret', $secret);
        $smarty->assign('group_id', $group_id);
        $smarty->assign('xml', $xml);
        $smarty->assign('load_available', $load_available);
        $smarty->assign('resize_photo', $resize_photo);
        $smarty->assign('loging', $loging);

        $write = true;
        
        $uri = $_SERVER['REQUEST_SCHEME'] ."://". $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
        header("Location: $uri");
    }

    $smarty->assign('err', $err);
}

if ($write === true) {
//-- сохраняем данные в файл
    $filename = 'config.php';
    if (!$handle = fopen($filename, 'w')) {
        echo "Не могу открыть файл " . $filename;
        exit;
    }
    $token = isset($token) ? $token : '';
    $user_id = isset($user_id) ? $user_id : '';
    
    
    $content = '<?php' . "\n"
            . '$app_id = \'' . $app_id . '\';' . "\n"
            . '$secret = \'' . $secret . '\';' . "\n"
            . '$group_id = \'' . $group_id . '\';' . "\n"
            . '$xml = \'' . $xml . '\';' . "\n"
            . '$load_available = \'' . $load_available. '\';' . "\n"
            . '$resize_photo = \'' . $resize_photo. '\';' . "\n"
            . '$loging = \'' . $loging. '\';' . "\n"
            . '$scopes = \'' . implode(',', array('market', 'photos', 'offline')) . '\';' . "\n"
            . '$token = \'' . $token . '\';' . "\n"
            . '$user_id = \'' . $user_id  . '\';' . "\n"
            . '$category_id = \'' . 606  . '\';' . "\n"
            . '$redirect_uri = \'' . $_SERVER['REQUEST_SCHEME'] ."://". $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . '\';' . "\n";

    fwrite($handle, $content);
    fclose($handle);
// --
}

$smarty->display('install.tpl');
