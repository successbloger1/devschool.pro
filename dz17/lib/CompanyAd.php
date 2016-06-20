<?php

class CompanyAd extends Ad {
    private $color;
    
    public function __construct($data = array()) {
        parent::__construct($data);
        
        $this->color = 'yellow';
    }
     
    public function getColor(){
        return $this->color;
    }
}
