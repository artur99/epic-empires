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
    function getCityInfo($cid){
        $stmt = $this->db->prepare("SELECT cities.*, users.username FROM cities INNER JOIN users ON cities.user_id = users.id WHERE cities.id = :cid LIMIT 1");
        $stmt->bindValue('cid', $cid);
        $stmt->execute();
        $r = $stmt->fetch();
        if(!isset($r['id'])){
            return false;
        }
        return $r;
    }

    function addTask($city_id, $user_id, $task, $param){
        if(!($city_data = $this->checkUserCity($user_id, $city_id))) return $this->err2();
        if($task == 'get' && in_array($param, ['food', 'gold', 'wood'])){
            return $this->addResourceTask($city_data, $param);
        }elseif($task == 'build' && in_array($param, ['center', 'academy', 'house', 'barracks'])){
            return $this->addBuildTask($city_data, $param);
        }elseif($task == 'train' && in_array($param, ['unit', 'archer'])){
            return $this->addUnitTask($city_data, $param);
        }else{
            return [
                'type' => 'error',
                'text' => 'Something really bad happened there. o.O'
            ];
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
            $this->resUpdate($city_data['id'], 0, 0, 0, -$required['workers']);
            $this->startTask($city_data['id'], 'get', $param, $required['workers'], $required['time'], $required['result']);
            return [
                'type' => 'success',
                'text' => 'Task started successfuly'
            ];
        }
    }

    private function addBuildTask($city_data, $param){
        $bd = \Misc\StaticData::buildingData();
        $b_level = intval($city_data['b_'.$param]);
        $required = $bd[$param][$b_level+1];
        $costs = isset($required['costs']) ? $required['costs'] : null;
        $req2['workers'] = isset($required['workers']) ? $required['workers'] : 0;
        $req2['food'] = isset($costs['food']) ? $costs['food'] : 0;
        $req2['wood'] = isset($costs['wood']) ? $costs['wood'] : 0;
        $req2['gold'] = isset($costs['gold']) ? $costs['gold'] : 0;
        $req2['time'] = isset($required['time']) ? $required['time'] : 0;
        $req2['result'] = isset($required['result']) ? $required['result'] : null;
        $err = false;
        if($this->buidTaskExists($city_data['id'], $param)){
            $err = 'You are already working on this building';
        }elseif(intval($city_data['r_workers']) < $req2['workers']){
            $err = 'You don\'t have enough free workers for this';
        }elseif(intval($city_data['r_food']) < $req2['food']){
            $err = 'You don\'t have enough food for this';
        }elseif(intval($city_data['r_wood']) < $req2['wood']){
            $err = 'You don\'t have enough wood for this';
        }elseif(intval($city_data['r_gold']) < $req2['gold']){
            $err = 'You don\'t have enough gold for this';
        }else{
            $this->resUpdate($city_data['id'], -$req2['food'], -$req2['wood'], -$req2['gold'], -$req2['workers']);
            $this->startTask($city_data['id'], 'build', $param, $req2['workers'], $required['time'], $required['result']);
            return [
                'type' => 'success',
                'text' => 'Task started successfuly'
            ];
        }
        return [
            'type' => 'error',
            'text' => $err
        ];
    }

    private function addUnitTask($city_data, $param){
        $ud = \Misc\StaticData::unitsData();
        $required = $ud[$param];
        $costs = isset($required['costs']) ? $required['costs'] : null;
        $req2['workers'] = isset($required['workers']) ? $required['workers'] : 0;
        $req2['food'] = isset($costs['food']) ? $costs['food'] : 0;
        $req2['wood'] = isset($costs['wood']) ? $costs['wood'] : 0;
        $req2['gold'] = isset($costs['gold']) ? $costs['gold'] : 0;
        $req2['time'] = isset($required['time']) ? $required['time'] : 0;
        $req2['result'] = isset($required['result']) ? $required['result'] : null;
        $err = false;
        if(intval($city_data['b_barracks']) <= 0){
            $err = 'You must first build a barrack';
        }elseif($param == 'archer' && intval($city_data['b_barracks']) <= 1){
            $err = 'Your barrack must have level 2 to train archers';
        }elseif(intval($city_data['r_food']) < $req2['food']){
            $err = 'You don\'t have enough food for this';
        }elseif(intval($city_data['r_wood']) < $req2['wood']){
            $err = 'You don\'t have enough wood for this';
        }elseif(intval($city_data['r_gold']) < $req2['gold']){
            $err = 'You don\'t have enough gold for this';
        }else{
            $this->resUpdate($city_data['id'], -$req2['food'], -$req2['wood'], -$req2['gold'], -$req2['workers']);
            $this->startTask($city_data['id'], 'train', $param, 0, $required['time'], $required['result']);
            return [
                'type' => 'success',
                'text' => 'Task started successfuly'
            ];
        }
        return [
            'type' => 'error',
            'text' => $err
        ];
    }

    function buidTaskExists($city_id, $param){
        $stmt = $this->db->prepare("SELECT id FROM tasks WHERE city_id = :cid AND param = :param LIMIT 1");
        $stmt->bindValue('cid', $city_id);
        $stmt->bindValue('param', $param);
        $stmt->execute();
        $r = $stmt->fetch();
        if(!isset($r['id'])){
            return false;
        }
        return true;
    }

    function startWar($uid, $cid, $tid, $units, $archers){
        if(!($city_data = $this->checkUserCity($uid, $cid))) return $this->err2();
        $err = false;
        $units = intval($units);
        $archers = intval($archers);
        $cst = \Misc\StaticData::warVars()['costs'];
        if($units == 0 && $archers == 0){
            $err = 'Please select at least one unit to send this attack';
        }elseif($city_data['units'] < $units){
            $err = 'You don\'t have enough units to send this attack';
        }elseif($city_data['archers'] < $archers){
            $err = 'You don\'t have enough archers to send this attack';
        }elseif(isset($cst['gold']) && $city_data['r_gold'] < $cst['gold']){
            $err = 'You don\'t have enough gold to send this attack';
        }elseif(isset($cst['wood']) && $city_data['r_wood'] < $cst['wood']){
            $err = 'You don\'t have enough wood to send this attack';
        }elseif(isset($cst['food']) && $city_data['r_food'] < $cst['food']){
            $err = 'You don\'t have enough food to send this attack';
        }else{
            $target_data = $this->getCityInfo($tid);
            $distance = sqrt(pow($target_data['loc_x'] - $city_data['loc_x'], 2) + pow($target_data['loc_y'] - $city_data['loc_y'], 2));
            $winfo = \Misc\StaticData::warVars();
            $dist_r = $distance * $winfo['distanceRate'];
            $time = $dist_r * $winfo['spmRate'];
            $param = [
                'units' => $units,
                'archers' => $archers
            ];
            $this->resUpdate($cid, isset($cst['food'])?-$cst['food']:0, isset($cst['wood'])?-$cst['wood']:0, isset($cst['gold'])?-$cst['gold']:0, 0, -$units, -$archers);
            $this->startTask($cid, 'attack', $param, 0, $time, $target_data['username'], $tid);
            return [
                'type' => 'success',
                'text' => 'Attack sent with success'
            ];
        }
        return [
            'type' => 'error',
            'text' => $err
        ];
    }

    function resUpdate($cid, $food = 0, $wood = 0, $gold = 0, $workers = 0, $units = 0, $archers = 0){
        $food = intval($food);
        $wood = intval($wood);
        $gold = intval($gold);
        $workers = intval($workers);
        $units = intval($units);
        $archers = intval($archers);
        $cid = intval($cid);

        $stmt = $this->db->prepare("UPDATE cities SET r_food = r_food + :food, r_wood = r_wood + :wood, r_gold = r_gold + :gold, r_workers = r_workers + :workers, units = units + :units, archers = archers + :archers WHERE id = :cid");
        $stmt->bindValue('food', $food);
        $stmt->bindValue('wood', $wood);
        $stmt->bindValue('gold', $gold);
        $stmt->bindValue('workers', $workers);
        $stmt->bindValue('units', $units);
        $stmt->bindValue('archers', $archers);
        $stmt->bindValue('cid', $cid);
        $stmt->execute();

        return true;
    }

    function startTask($city_id, $type, $param, $workers, $time, $result = null, $target = null){
        $time_s = time();
        $time_e = time() + $time;
        $city_id = intval($city_id);
        $workers = intval($workers);
        if(is_array($param)){
            $param = json_encode($param);
        }
        if($target){
            $target = intval($target);
        }

        $stmt = $this->db->prepare("INSERT INTO tasks (type, city_id, workers, target, time_s, time_e, param, result) VALUES (:type, :city_id, :workers, :target, :time_s, :time_e, :param, :result)");
        $stmt->bindValue('type', $type);
        $stmt->bindValue('city_id', $city_id);
        $stmt->bindValue('target', $target);
        $stmt->bindValue('workers', $workers);
        $stmt->bindValue('time_s', $time_s);
        $stmt->bindValue('time_e', $time_e);
        $stmt->bindValue('param', $param);
        $stmt->bindValue('result', $result ? json_encode($result) : $result);

        $stmt->execute();
        return true;
    }

    function getTasks($user_id, $city_id){
        if(!($city_data = $this->checkUserCity($user_id, $city_id))) return $this->err2();
        $this->autoGameCron();
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE city_id = :city_id");
        $stmt->bindValue('city_id', $city_id);
        $stmt->execute();

        $ac_level = $city_data['b_academy'];

        $results = $stmt->fetchAll();
        $tnd = \Misc\StaticData::taskNames();
        $rd = \Misc\StaticData::resourceData();
        foreach($results as $k => $r){
            $results[$k]['taskname'] = is_array($tnd[$r['type']])?$tnd[$r['type']][$r['param']]:$tnd[$r['type']];
            if($r['type'] == 'get'){
                $results[$k]['result'] = $rd[$ac_level][$r['param']]['result'];
            }elseif($r['type'] == 'build'){
                $results[$k]['result'] = null;//$rd[$ac_level][$r['param']]['result'];
            }elseif($r['type'] == 'attack'){
                $results[$k]['result'] = null;
            }
        }
        return $results;
    }

    function delTask($task_id, $user_id){
        $stmt = $this->db->prepare("SELECT tasks.*, cities.user_id as user_id FROM tasks INNER JOIN cities ON cities.id = tasks.city_id WHERE tasks.id = :id LIMIT 1");
        $stmt->bindValue('id', $task_id);
        $stmt->execute();
        $r = $stmt->fetch();
        if(!isset($r['user_id']) || $r['user_id'] != $user_id){
            return $this->err2();
        }else{
            if($r['type'] == 'building' || $r['type'] == 'get'){
                $stmt = $this->db->prepare("UPDATE cities SET r_workers = r_workers + :wk LIMIT 1");
                $stmt->bindValue('wk', intval($r['workers']));
                $stmt->execute();
            }
            $stmt2 = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
            $stmt2->bindValue('id', $task_id);
            $stmt2->execute();
            return [
                'type' => 'success',
                'text' => 'Task canceled'
            ];
        }
    }
    function getAttacks($uid, $cid){
        if(!($city_data = $this->checkUserCity($uid, $cid))) return $this->err2();
        $stmt = $this->db->prepare("SELECT tasks.id, tasks.time_e, cities.loc_x, cities.loc_y FROM tasks INNER JOIN cities ON cities.id = tasks.city_id WHERE type = 'attack' AND target = :cid AND (time_e - UNIX_TIMESTAMP()) < 180");
        $stmt->bindValue('cid', $cid);
        $stmt->execute();

        $stmt2 = $this->db->prepare("SELECT tasks.id, tasks.time_e, tasks.time_s, tasks.param, cities.loc_x, cities.loc_y FROM tasks INNER JOIN cities ON cities.id = tasks.target WHERE type = 'attack' AND city_id = :cid");
        $stmt2->bindValue('cid', $cid);
        $stmt2->execute();

        return [
            'ingoing' => $stmt->fetchAll(),
            'outgoing' => $stmt2->fetchAll(),
        ];
    }
    function getReports($uid, $cid){
        if(!($city_data = $this->checkUserCity($uid, $cid))) return $this->err2();
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE city_id = :cid ORDER BY time DESC LIMIT 10");
        $stmt->bindValue('cid', $cid);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function autoGameCron(){
        $cron = new \Misc\GameCron($this->db);
        $cron->run();
        return true;
    }

    function err(){
        return [
            'type' => 'error',
            'text' => 'Please refresh the page'
        ];
    }
    function err2(){
        return [
            'type' => 'error',
            'text' => 'This is not your city'
        ];
    }
}
