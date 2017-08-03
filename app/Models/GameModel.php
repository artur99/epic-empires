<?php

namespace Models;

use PDO;
use Misc\StaticData;

class GameModel extends BaseModel{
    function getCitiesAround($x, $y){
        $stmt = $this->db->prepare("SELECT cities.id, cities.name, cities.user_id, cities.loc_x, cities.loc_y, cities.level, cities.points, users.username as username FROM cities INNER JOIN users ON cities.user_id = users.id WHERE ABS(loc_x - :x) < 4 && ABS(loc_y - :y) < 4");
        $stmt->bindValue('x', $x);
        $stmt->bindValue('y', $y);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function checkUserCity($uid, $cid){
        $stmt = $this->db->prepare("SELECT * FROM cities WHERE id = :cid AND user_id = :uid LIMIT 1");
        $stmt->bindValue('cid', $cid);
        $stmt->bindValue('uid', $uid);
        $stmt->execute();
        $r = $stmt->fetch();
        if(!isset($r['id'])){
            return false;
        }
        return $r;
    }

    function addTask($city_id, $user_id, $task, $param){
        if(!($city_data = $this->checkUserCity($user_id, $city_id)){
            return [
                'type' => 'error',
                'text' => 'This is not your city'
            ];
        }
        if($task == 'get' && in_array($param, ['food', 'gold', 'wood'])){
            return $this->addResourceTask($city_data, $param);
        }
    }

    private function addResourceTask($city_data, $param){
        $rd = \Misc\StaticData::resourceData();
        $ac_level = intval($city_data['b_academy']);
        $required = $rd[$ac_level][$param];
        if(intval($city_data['r_workers']) < $required['workers']){
            return [
                'type' => 'error',
                'text' => 'You don\'t have enough free workers for this'
            ];
        }else{
            $this->resUpdate(0, 0, 0, $required['workers']);
            $this->startTask($city_data['id'], 'get', $param, $required['time']);
        }

    }

    function resUpdate($food = 0, $wood = 0, $gold = 0, $workers = 0){
        $food = intval($food);
        $wood = intval($wood);
        $gold = intval($gold);
        $workers = intval($workers);
        $stmt = $this->db->prepare("UPDATE cities SET r_food = r_food + :food, r_wood = r_wood + :wood, r_gold = r_gold + :gold, r_workers = r_workers + :workers");
        $stmt->bindValue('food', $food);
        $stmt->bindValue('wood', $wood);
        $stmt->bindValue('gold', $gold);
        $stmt->bindValue('workers', $workers);
        $stmt->execute();
        return true;
    }

    function startTask($city_id, $type, $param, $time, $target = null){
        $time_s = time();
        $time_e = time() + $time;

        $stmt = $this->db->prepare("INSERT INTO tasks (type, city_id, target, time_s, time_e, param) VALUES (:type, :city, :target, :time_s, :time_e, :param)");
        $stmt->bindValue('type', $type);
        $stmt->bindValue('city_id', $city_id);
        $stmt->bindValue('target', $target);
        $stmt->bindValue('time_s', $time_s);
        $stmt->bindValue('time_e', $time_e);
        $stmt->bindValue('param', $param);

        $stmt->execute();
        return true;
    }

    function err(){
        return [
            'type' => 'error',
            'text' => 'Please refresh the page'
        ];
    }
}
