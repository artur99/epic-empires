<?php

namespace Models;

class BaseModel{
    protected $db;

    function __construct($db, $session = null){
        $this->db = $db;
        $this->session = $session;
    }
}
