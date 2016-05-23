<?php

class AdsStore {
    private static $instance = NULL;
    private $ads = array();
    
    private $citys = array();
    private $categorys = array();
    
    function __construct(){
        global $db;
        global $smarty;
        
        $this->citys = $db->selectCol('SELECT id AS ARRAY_KEY, name FROM location');
        $this->categorys = $db->selectCol('SELECT id AS ARRAY_KEY, name FROM category');
        
        $smarty->assign('citys', $this->citys);
        $smarty->assign('category', $this->categorys);
    }
    
    public function instance() {
        if(self::$instance == NULL){
            self::$instance = new AdsStore();
        }
            return self::$instance;
    }
    
    public function addAds(Ad $ad) {
       if(!($this instanceof AdsStore)){
           die("Нельзя использовать этот метод в конструкторе классов");
       } 
       $this->ads[$ad->id] = $ad;
    }
    
    public function getAd($id) {
        return isset($this->ads[$id]) ? $this->ads[$id] : null;
    }
    
    public function getAllAds() {
        return $this->ads;
    }
    
    public function getAllAdsFromDb() {
        global $db;
        
        $all = $db->select('SELECT * FROM ads');
        
        foreach ($all as $value) {
            $ad = new Ad ($value);
            self::addAds($ad);
        }
        
        return $this;
    }
    
    public static function deleteAllAds () {
        global $db;
        
        return $db->query('TRUNCATE TABLE ads');
    }
    
    public function sortAds ($sort) {
        global $db;
        
        $order = isset($_COOKIE['order']) ? $_COOKIE['order'] : '';
        
        $allowed = array('title', 'price'); //перечисляем варианты
        $key = array_search($sort, $allowed); // ищем среди них переданный параметр
        $orderby = $allowed[$key]; //выбираем найденный (или, за счёт приведения типов - первый) элемент. 
        $order = ($order == 'DESC') ? 'ASC' : 'DESC'; // определяем направление сортировки
    
        setcookie('order', $order);
        
        $result = array();
        foreach($this->ads as $id => $ad){
            $temp[$id] = $ad->$orderby;
        }
        switch ($order) {
            case 'DESC':
                arsort($temp);
                break;
            
            case 'ASC':
                asort($temp);
                break;

            default:
                break;
        }
        foreach($temp as $id => $key){
            $result[$id] = $this->ads[$id];
        }
        $this->ads = $result;
        
        return $this->ads;
    }
    
    public function printAds(){
        global $smarty;
        
        $row = '';
        $n = 1;
        foreach($this->ads as $value){
            $smarty->assign('n', $n);
            $n++;
            $smarty->assign('ad', $value);
            $row .= $smarty->fetch('table_row.tpl.html');
        }
        $smarty->assign('ads_rows', $row);
        
    }
    
}
