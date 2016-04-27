<?php

class Ad {
    public $private;
    public $seller_name;
    public $email;
    public $allow_mails;
    public $phone;
    public $location_id;
    public $category_id;
    public $title;
    public $description;
    public $price;
    
    protected $ad_data = array();

    public $id;

    public function __construct($data = array()) {
        global $db;

        if (isset($_GET['id']) || isset($_GET['delete'])){
            
            $this->id = isset($_GET['id']) ? $_GET['id'] : $_GET['delete'];
            if (empty($_POST)){
                $data = $db->select("SELECT * FROM ads WHERE id = ?", $this->id);
                $data = $data['0'];
            }
        } else {
            $this->id = uniqid();
        }
        
        $this->ad_data = $data;

        foreach($data as $key => $value){
            $this->$key = $value;
        }
    }

    public function addAd(){
        global $db;
        
        $ad = array_merge(array('id' => $this->id), $this->ad_data);
        $db->query('INSERT INTO ads (?#) VALUES (?a)', array_keys($ad), array_values($ad));
        
    }
    
    function saveAd() {
        global $db;
        
        $db->query('UPDATE ads SET ?a WHERE id = ?', $this->ad_data, $this->id); 
    }
    
    function deleteAd () {
        global $db;
        
        $db->query('DELETE FROM ads WHERE id = ?', $this->id);
    }
    
    public static function deleteAllAds () {
        global $db;
        
        $db->query('TRUNCATE TABLE ads');
    }
    
    public static function getAdsList() {
        global $db;
        
        return $db->query('SELECT * FROM ads');
    }
    
    public static function getSortAdsList ($sort) {
        global $db;
        
        $order = isset($_COOKIE['order']) ? $_COOKIE['order'] : '';
        
        $allowed = array('title', 'price'); //перечисляем варианты
        $key = array_search($sort, $allowed); // ищем среди них переданный параметр
        $orderby = $allowed[$key]; //выбираем найденный (или, за счёт приведения типов - первый) элемент. 
        $order = ($order == 'DESC') ? 'ASC' : 'DESC'; // определяем направление сортировки
    
        setcookie('order', $order);
    
        return $db->query("SELECT * FROM ads ORDER BY $orderby $order");
    }
}

?>