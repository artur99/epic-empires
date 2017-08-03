<?php
namespace Misc;

class GameCron{
    function __construct($db){
        $this->db = $db;
    }
    function run(){
        $q = $this->db->query("SELECT tasks.*, cities.level as city_level FROM tasks INNER JOIN cities ON cities.id = tasks.city_id WHERE time_e <= UNIX_TIMESTAMP()");
        $maxes = \Misc\StaticData::resMax();
        $tasks = $q->fetchAll();
        foreach($tasks as $task){
            if($task['type'] == 'get'){
                $res = json_decode($task['result']);
                $f = isset($res->food) ? $res->food : 0;
                $w = isset($res->wood) ? $res->wood : 0;
                $g = isset($res->gold) ? $res->gold : 0;
                $wo = isset($res->workers) ? intval($res->workers) : 0;
                $p = isset($res->points) ? intval($res->points) : 0;
                $wo += $task['workers'];
                $max = $maxes[$task['city_level']];;
                $this->resUpdate($task['city_id'], $f, $w, $g, $wo, $p, $max);
                $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                $stmt->bindValue('id', $task['id']);
                $stmt->execute();
            }elseif($task['type'] == 'build'){
                $res = json_decode($task['result']);
                $f = isset($res->food) ? $res->food : 0;
                $w = isset($res->wood) ? $res->wood : 0;
                $g = isset($res->gold) ? $res->gold : 0;
                $wo = isset($res->workers) ? intval($res->workers) : 0;
                $p = isset($res->points) ? intval($res->points) : 0;
                $wo += $task['workers'];
                $this->resUpdate($task['city_id'], $f, $w, $g, $wo, $p);
                $this->buildingLvlUpdate($task['city_id'], $task['param']);
                $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                $stmt->bindValue('id', $task['id']);
                $stmt->execute();
            }
        }
    }
    function resUpdate($cid, $food = 0, $wood = 0, $gold = 0, $workers = 0, $points = 0, $max = 99999){
        $food = intval($food);
        $wood = intval($wood);
        $gold = intval($gold);
        $points = intval($points);
        $workers = intval($workers);
        $cid = intval($cid);

        $stmt = $this->db->prepare("UPDATE cities SET r_food = LEAST(r_food + :food, :max_r), r_wood = LEAST(r_wood + :wood, :max_r), r_gold = LEAST(r_gold + :gold, :max_r), r_workers = r_workers + :workers, points = points + :points WHERE id = :cid");
        $stmt->bindValue('food', $food);
        $stmt->bindValue('wood', $wood);
        $stmt->bindValue('gold', $gold);
        $stmt->bindValue('workers', $workers);
        $stmt->bindValue('points', $points);
        $stmt->bindValue('cid', $cid);
        $stmt->bindValue('max_r', $max);
        $stmt->execute();

        return true;
    }
    function buildingLvlUpdate($city_id, $building){
        $items = [
            'house' => 'b_house',
            'academy' => 'b_academy',
            'barracks' => 'b_barracks',
            'center' => 'b_center',
            'level' => 'level'
        ];
        if(!isset($items[$building])) return;
        $stmt = $this->db->prepare("UPDATE cities SET ".$items[$building]." = ".$items[$building]." + 1 WHERE id = :id LIMIT 1");
        $stmt->bindValue('id', $city_id);
        $stmt->execute();
    }
}
