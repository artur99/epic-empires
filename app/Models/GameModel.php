<?php

namespace Models;

use PDO;
use Misc\StaticData;

class GameModel extends BaseModel{
    function getCitiesAround($x, $y){
        $stmt = $this->db->prepare("SELECT * FROM cities WHERE ABS(loc_x - :x) < 4 && ABS(loc_y - :y) < 4");
        $stmt->bindValue('x', $x);
        $stmt->bindValue('y', $y);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function err(){
        return [
            'type' => 'error',
            'text' => 'Please refresh the page'
        ];
    }
}
