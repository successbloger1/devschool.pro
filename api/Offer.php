<?php

class Offer {
    public $id;
    public $name;
    public $description;
    public $price;
    public $available;
    public $photo;
    public $url;
    public $category_id;
    
    public function __construct($simple_offer) {
        $this->id = (string)$simple_offer['id'];
        $this->name = (string)$simple_offer->name;
        $this->description = (string)$simple_offer->description;
        $this->price = (string)$simple_offer->price;
        $this->available = $simple_offer['available'] == 'true' ? true : false;
        $this->url = (string)$simple_offer->url;
        $this->photo = isset($simple_offer->picture[1]) ? (string)$simple_offer->picture[1] : $simple_offer->picture;
        $this->category_id = (string)$simple_offer->categoryId;
    }
    
    public function savePhoto(){
        // Сохраняем фото на локальный сервер
            $url = $this->photo;
            $path = __DIR__.'/images/' .$this->id . '.jpg';
            file_put_contents($path, file_get_contents($url));
            
            $this->photo = $path;
            // ----------------------------------
    }

}
