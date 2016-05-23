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
    
    private $err = array();

    public function __construct($data = array()) {
        global $db;
        
        if(isset($data['id'])){
            $this->id = $data['id'];
        } else {
            $this->id = uniqid();
        }
        
        $data = $this->validation($data);
        
        $this->ad_data = $data;

        foreach($data as $key => $value){
            $this->$key = $value;
        }
        
    }

    public function save(){
        global $db;
        
        $ad = array_merge(array('id' => $this->id), $this->ad_data);
        
        $db->query('REPLACE INTO ads (?#) VALUES (?a)', array_keys($ad), array_values($ad));
        
    }
        
    function delete() {
        global $db;
        
        return $db->query('DELETE FROM ads WHERE id = ?', $this->id);
    }
        
    public function validation (array $form_data){
            
        foreach ($form_data as $key => $value) {
            $value = trim($value); // Убираем пробелы по краям
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value); //Убираем слеши, если надо  
            }
            $value = htmlspecialchars($value, ENT_QUOTES); //Заменяем служебные символы HTML на эквиваленты  
            $form_data[$key] = $value;
        }
        
        $this->err = '';
        
        $private = (int) $form_data['private'];
    
        $seller_name = $form_data['seller_name'];
        if (empty($seller_name)) {
            $this->err .= 'Поле "Имя" обязательно для заполнения<br/>';
        }
    
        $email = $form_data['email'];
        if (!preg_match('/^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,4}$/i', $email) && !empty($email)) {
            $this->err .= 'Неверный Email<br/>';
        }
    
        $allow_mails = isset($form_data['allow_mails']) ? $form_data['allow_mails'] : '';
    
        $phone = $form_data['phone'];
        if (!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $phone) && !empty($phone)) {
            $this->err .= 'Неверный телефонный номер<br/>';
        }
    
        $location_id = (int) $form_data['location_id'];
        $category_id = (int) $form_data['category_id'];
    
        $title = $form_data['title'];
        if (empty($title)) {
            $this->err .= 'Поле "Название" обязательно для заполнения<br/>';
        }
        
        $description = $form_data['description'];
    
        $price = $form_data['price'];
        if (empty($price)) {
            $this->err .= 'Поле "Цена" обязательно для заполнения<br/>';
        }
        if (!preg_match('/^(?:\d+|\d*\.\d+)$/', $price) && !empty($price)) {
            $this->err .= 'Неверно указана цена<br/>';
        }
    
        return array(   
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
                    );
    }
    
    function getErrors(){
        
        return $this->err;
    }
}


?>