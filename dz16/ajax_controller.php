<?php 
require_once 'config.php';

switch ($_GET['action']) {
    case "delete": {

            if ($_GET['id'] == '0') {
                $main->deleteAllAds();
                $result['status'] = 'success';
                $result['message'] = 'Обяъвления успешно удалены';
            } else {
                $main->getAd($_GET['id'])->delete();
                $result['status'] = 'success';
                $result['message'] = 'Обяъвление ' . $_GET['id'] . ' успешно удалено';
            }
            break;
        }

    case "edit":{
        $ad = $main->getAd($_GET['id']);
        $result['status'] = 'success';
        $result['message'] = 'Обяъвление '.$_GET['id'].' отправлено на редактирование';
        $result['data'] = $ad;
        
        break;
    }
    
    case "save":
    case "create":{
        
        if (isset($_GET['id'])) {
                $data = array_merge(array('id' => $_GET['id']), $_POST);
                $flag = 'отредактированно';
            } else {
                $data = $_POST;
                $flag = 'создано';
            }

            $ad = new Ad($data);

            if ($ad->getErrors() != '') {
                $result['status'] = 'error';
                $result['message'] = $ad->getErrors();
            } else {
                $ad->save();
                $result['status'] = 'success';
                $result['message'] = 'Обяъвление ' . $ad->id . ' успешно '.$flag;
                $result['data'] = $ad;
                $smarty->assign('ad', $ad);
                $result['row'] = $smarty->fetch('table_row.tpl.html');
                $result['table'] = $smarty->fetch('table.tpl.html');
            }
            
            break;
        }

    default:
        $result['status'] = 'error';
        $result['message'] = 'Такое действие не допустимо';
        break;
}

echo json_encode($result);

