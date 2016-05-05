<?php

class Worker {

    private $offers = array();
    private $offers_vk = array();
    private $synchro = array();
    private $group_id;
    private $sleep = 1;
    private $errors = '';
    private $filename = 'synchro.php';
    private $time;

    public function __construct($group_id) {
        file_put_contents('log.txt', "");
        
        $this->group_id = $group_id;
        $this->time = time();
        
        $this->debugToFile('Старт скрипта: ' . date('d.m.Y h:i',$this->time));
        
        if (file_exists($this->filename)) {
                $this->synchro = include ($this->filename);
        }
    }
    
    public function parseXml($xml) {
        global $load_available;
        
        $simplexml = simplexml_load_file($xml);

//        print_r($simplexml);
//        echo '<hr>';

        foreach ((array) $simplexml->shop->offers as $offer) {
            foreach ($offer as $of) {
                if (empty($load_available)) {
                   if($of['available'] == 'false') continue; 
                }
                $this->offers[] = new Offer($of);
            }
        }

        return $this->offers;
    }

    public function parseMarket() {
        global $vk;

        $count = 200;
        $offset = 0;

        do {
            $result = $vk->api('market.get', [

                'owner_id' => '-' . $this->group_id,
                'album_id' => 0,
                'count' => $count,
                'offset' => $offset,
                'extended' => 0
            ]);


            $all = $result[0];
            unset($result[0]);

            foreach ($result as $items) {
                $this->offers_vk[] = $items;
            }
            $offset += $count;
        } while ($all > $offset);

        return $this->offers_vk;
    }

    public function inSynchrofile(Offer $offer) {

        foreach ($this->synchro as $key => $synchro) {
            if (($offer->id == $synchro['xml_id'])) {
                if (date('d.m.Y h:i', $this->time) != date('d.m.Y h:i', $synchro['date'])) {
                    $vk_id = $synchro['vk_id'];
                } else {
                    $vk_id = '';
                }
                break;
            }
        }
        return isset($vk_id) ? $vk_id : false;
    }

    public function deleteNotSynchronized() {
        global $vk;

        foreach ($this->synchro as $key => $synchro) {
            if (date('d.m.Y h:i', $synchro['date']) != date('d.m.Y h:i', $this->time)) {
                unset($this->synchro[$key]);
                
                sleep($this->sleep);
                $vk->deleteFromMarket($this->group_id, $synchro['vk_id']);

                $this->debugToFile($synchro['xml_id'] . ' - Удален');
            }
        }
    }

    private function array_column($array,$column_name) {

        return array_map(function($element) use($column_name){return $element[$column_name];}, $array);

    }
    
    public function deleteFromMarket($group_id, $id) {
        global $vk;
        if (!empty($this->synchro)){
            if (array_search($id, $this->array_column($this->synchro,'vk_id')) === false) {
                
                sleep($this->sleep);
                
                $vk->deleteFromMarket($this->group_id, $id);
                $this->debugToFile($id . ' - Удален товар из VK');
            }
        } else {
            $vk->deleteFromMarket($this->group_id, $id);
            $this->debugToFile($id . ' - Удален товар из VK');
        }
        

    }

    public function editMarket(Offer $offer, $category_id, $id) {
        global $vk;
        sleep($this->sleep);

//        if ($offer->available == true) {

            if ($this->sizeCorrect($offer)) {

                $response = $vk->loadPhotoToMarket($offer, $this->group_id);

                if (is_array($response)) {
                    $successfully = $vk->editMarket($offer, $this->group_id, $category_id, $response[0]->pid, $id);
                    if (!isset($successfully->error)) {
                        foreach ($this->synchro as $key => $value) {
                            if (in_array($id, $value)) {
                                $this->synchro[$key]['date'] = (string) $this->time;
                                
                                break;
                            }
                        }
                        $this->debugToFile($offer->id . ' - Отредактирован');
                    } else {
                        // Логируем ошибку
                        $this->debugToFile($offer->id . ' - Ошибка редактирования');
                        return false;
                    }
                } else {
                    // Логируем ошибку
                    $this->debugToFile($offer->id . ' - Ошибка: ' . $response->error);
                    return false;
                }
            } else {
                // Логируем ошибку
                $this->debugToFile($offer->id . ' - Ошибка: Неверный размер картинки');
                return false;
            }
//        } else {
//            // Логируем ошибку
//            $this->debugToFile($offer->id . ' - Ошибка: available = false');
//            return false;
//        }
    }

    private function sizeCorrect(Offer $offer) {
        global $resize_photo;
        $offer->savePhoto();
        
        if (empty($offer->photo))
            return false;

        $image = array_values(getimagesize($offer->photo));

        //use list on new array
        list($width, $height) = $image;
        
        if (!empty($resize_photo)){
            if (!($width >= 400 && $height >= 400)) {
                $this->resizePhoto($offer->photo);
            }
            return true;
        } else {
            if ($width >= 400 && $height >= 400) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function resizePhoto($url){

// задание минимальный ширины и высоты
        $width = 400;
        $height = 400;

// получение новых размеров
        list($width_orig, $height_orig) = getimagesize($url);

        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height < $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }
// ресэмплирование
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($url);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// вывод
        imagejpeg($image_p, $url);
    }


    public function addToMarket(Offer $offer, $category_id) {
        global $vk;
        sleep($this->sleep);

        if ($this->sizeCorrect($offer)) {

            $response = $vk->loadPhotoToMarket($offer, $this->group_id);

            if (is_array($response)) {
                $id = $vk->addToMarket($offer, $this->group_id, $category_id, $response[0]->pid);
                if (isset($id->market_item_id)) {
                    $this->synchro[] = array(
                        'xml_id' => $offer->id,
                        'vk_id' => $id->market_item_id,
                        'date' => $this->time
                    );
                    $this->debugToFile($offer->id . ' - Добавлен');
                } else {
                    // Логируем ошибку
                    $this->debugToFile($offer->id . ' - Ошибка: ' . $id->error->error_msg);
                    return false;
                }
            } else {
                // Логируем ошибку
                $this->debugToFile($offer->id . ' - Ошибка: ' . $response->error);
                return false;
            }
        } else {
            // Логируем ошибку
            $this->debugToFile($offer->id . ' - Ошибка: Неверный размер картинки');
            return false;
        }
    }

    public function addAllToMarket(array $parse, $category_id) {
        global $vk;

        foreach ($parse as $offer) {
            $this->addToMarket($offer, $category_id);
        }
    }

    public function saveSynchro() {
        $this->arrayToFile($this->synchro);

        $this->debugToFile('Конец скрипта: ' . date('d.m.Y h:i', time()));
    }

    private function arrayToFile($array, $file = 0) {
        $level = 1;
        if ($file == 0) {
            $level = 0;
            $file = fopen($this->filename, "w");
            if (!$file) {
                return false;
            }
            fwrite($file, "<" . "?php \n return ");
        }

        $cnt = count($array);
        $i = 0;
        fwrite($file, "\narray(\n");
        foreach ($array as $key => $value) {
            if ($i++ != 0) {
                fwrite($file, ",\n");
            }
            if (is_array($array[$key])) {
                fwrite($file, "'$key' => ");
                $this->arrayToFile($array[$key], $file);
            } else {
                $value = addcslashes($value, "'" . "\\\\");
                fwrite($file, str_repeat(' ', ($level + 1) * 2) . "'$key' => '$value'");
            }
        }
        fwrite($file, ")");

        if ($level == 0) {
            fwrite($file, ";\n?" . ">");
            fclose($file);
            return true;
        }
    }

    public function getSynchro() {
        return $this->synchro;
    }

    private function debugToFile($data) {
        global $loging;
        
        if (!empty($loging)) error_log($data . "\n", 3, 'log.txt');
        else return;
    }

}
