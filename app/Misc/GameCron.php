<?php
namespace Misc;

class GameCron{
    function __construct($db){
        $this->db = $db;
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

    function run(){
        $q = $this->db->query("SELECT tasks.*, cities.level as city_level, cities.units, cities.archers FROM tasks INNER JOIN cities ON cities.id = tasks.city_id WHERE time_e <= UNIX_TIMESTAMP()");
        // $maxes = \Misc\StaticData::resMax();
        $tasks = $q->fetchAll();
        foreach($tasks as $task){
            if($task['type'] == 'get'){
                $res = json_decode($task['result']);
                $f = isset($res->food) ? $res->food : 0;
                $w = isset($res->wood) ? $res->wood : 0;
                $g = isset($res->gold) ? $res->gold : 0;
                $wo = isset($res->workers) ? intval($res->workers) : 0;
                $p = isset($res->points) ? intval($res->points) : 0;
                $wo += intval($task['workers']);
                $max = \Misc\StaticData::resMax($task['city_level']);
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
                $wo += intval($task['workers']);
                $this->resUpdate($task['city_id'], $f, $w, $g, $wo, $p);
                $this->buildingLvlUpdate($task['city_id'], $task['param']);
                if($task['param'] == 'center')
                    $this->buildingLvlUpdate($task['city_id'], 'level');
                $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                $stmt->bindValue('id', $task['id']);
                $stmt->execute();
            }elseif($task['type'] == 'attack'){
                $city_attk = $this->getCityInfo($task['city_id']);
                $city_target = $this->getCityInfo($task['target']);
                $unz = @json_decode($task['param']);

                $attk_city_id = $city_attk['id'];
                $attk_id = $city_attk['user_id'];
                $attk_username = $city_attk['username'];

                $target_city_id = $city_target['id'];
                $target_id = $city_target['user_id'];
                $target_username = $city_target['username'];

                $units_attacker = [
                    'units' => intval(isset($unz->units)?$unz->units:0),
                    'archers' => intval(isset($unz->archers)?$unz->archers:0)
                ];
                $units_target = [
                    'units' => intval($city_target['units']),
                    'archers' => intval($city_target['archers'])
                ];
                $battleResults = $this->runBattle($units_attacker, $city_attk['level'], $units_target, $city_target['level']);
                $unitsInitial = [
                    'attk' => $units_attacker,
                    'target' => $units_target
                ];
                $unitsFinal = [
                    'attk' => $battleResults['attacker'],
                    'target' => $battleResults['target']
                ];
                $attk_lost['units'] = ($unitsFinal['attk']['units'] - $unitsFinal['attk']['units']);
                $attk_lost['archers'] = ($unitsInitial['attk']['archers'] - $unitsFinal['attk']['archers']);
                $target_lost['units'] = ($unitsInitial['target']['units'] - $unitsFinal['target']['units']);
                $target_lost['archers'] = ($unitsInitial['target']['archers'] - $unitsFinal['target']['archers']);
                // var_dump($unitsInitial, $unitsFinal, $battleResults, $attk_lost, $target_lost);die();
                // var_dump($units_attacker, $units_target, $battleResults);die();
                // var_dump($attk_lost, $target_lost);die();

                $food = $city_target['r_food'];
                $wood = $city_target['r_wood'];
                $gold = $city_target['r_gold'];
                $prd = ['food' => $food, 'wood' => $wood, 'gold' => $gold];
                if($battleResults['won'] == true){
                    //He won the battle

                    $max = $maxes[$task['city_level']];

                    // $this->resUpdate($attk_city_id, $food, $wood, $gold, 0, 0, $max, $unitsFinal['attk']['units'], $unitsFinal['attk']['archers']);
                    $this->resUpdate2($attk_city_id, [
                        'food' => $food,
                        'wood' => $wood,
                        'gold' => $gold,
                        'max' => $max,
                        'units' => $unitsFinal['attk']['units'],
                        'archers' => $unitsFinal['attk']['archers'],
                    ]);
                    // $this->resUpdate($target_city_id, -$food, -$wood, -$gold, 0, 0, 99999, -$target_lost['units'], -$target_lost['archers']);
                    $this->resUpdate2($target_city_id, [
                        'food' => -$food,
                        'wood' => -$wood,
                        'gold' => -$gold,
                        'units' => -$target_lost['units'],
                        'archers' => -$target_lost['archers'],
                    ]);

                    $this->sendReport($attk_id, $attk_city_id, 'won', $target_username, [$unitsInitial, $unitsFinal, $prd], $task['time_e']);
                    $this->sendReport($target_id, $target_city_id, 'attacked', $attk_username, [$unitsInitial, $unitsFinal, $prd], $task['time_e']);

                    $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                    $stmt->bindValue('id', $task['id']);
                    $stmt->execute();
                }else{
                    $this->resUpdate2($target_city_id, [
                        'units' => -$target_lost['units'],
                        'archers' => -$target_lost['archers']
                    ]);

                    $this->sendReport($attk_id, $attk_city_id, 'lost', $target_username, [$unitsInitial, $unitsFinal], $task['time_e']);
                    $this->sendReport($target_id, $target_city_id, 'resisted', $attk_username, [$unitsInitial, $unitsFinal], $task['time_e']);

                    $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                    $stmt->bindValue('id', $task['id']);
                    $stmt->execute();
                }
            }elseif($task['type'] == 'train'){
                $res = json_decode($task['result']);
                $u = isset($res->unit) ? intval($res->unit) : 0;
                $a = isset($res->archer) ? intval($res->archer) : 0;
                $p = isset($res->points) ? intval($res->points) : 0;
                $max = $maxes[$task['city_level']];
                $this->resUpdate($task['city_id'], 0, 0, 0, 0, $p, 99999, $u, $a);
                $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                $stmt->bindValue('id', $task['id']);
                $stmt->execute();
            }
        }
    }
    function resUpdate($cid, $food = 0, $wood = 0, $gold = 0, $workers = 0, $points = 0, $max = 99999, $units = 0, $archers = 0){
        $food = intval($food);
        $wood = intval($wood);
        $gold = intval($gold);
        $workers = intval($workers);
        $points = intval($points);
        $units = intval($units);
        $archers = intval($archers);
        $cid = intval($cid);

        $stmt = $this->db->prepare("UPDATE cities SET r_food = LEAST(r_food + :food, :max_r), r_wood = LEAST(r_wood + :wood, :max_r), r_gold = LEAST(r_gold + :gold, :max_r), r_workers = r_workers + :workers, points = points + :points, units = units + :units, archers = archers + :archers WHERE id = :cid");
        $stmt->bindValue('food', $food);
        $stmt->bindValue('wood', $wood);
        $stmt->bindValue('gold', $gold);
        $stmt->bindValue('workers', $workers);
        $stmt->bindValue('units', $units);
        $stmt->bindValue('archers', $archers);
        $stmt->bindValue('points', $points);
        $stmt->bindValue('cid', $cid);
        $stmt->bindValue('max_r', $max);
        $stmt->execute();

        return true;
    }
    function resUpdate2($cid, $data = []){//$food = 0, $wood = 0, $gold = 0, $workers = 0, $points = 0, $max = 99999, $units = 0, $archers = 0){
        $food = isset($data['food']) ? intval($data['food']) : 0;
        $wood = isset($data['wood']) ? intval($data['wood']) : 0;
        $gold = isset($data['gold']) ? intval($data['gold']) : 0;
        $workers = isset($data['workers']) ? intval($data['workers']) : 0;
        $points = isset($data['points']) ? intval($data['points']) : 0;
        $units =  isset($data['units']) ? intval($data['units']) : 0;
        $archers =  isset($data['archers']) ? intval($data['archers']) : 0;
        $max =  isset($data['max']) ? intval($data['max']) : 999999;
        $cid = intval($cid);

        $stmt = $this->db->prepare("UPDATE cities SET r_food = LEAST(r_food + :food, :max_r), r_wood = LEAST(r_wood + :wood, :max_r), r_gold = LEAST(r_gold + :gold, :max_r), r_workers = r_workers + :workers, points = points + :points, units = units + :units, archers = archers + :archers WHERE id = :cid");
        $stmt->bindValue('food', $food);
        $stmt->bindValue('wood', $wood);
        $stmt->bindValue('gold', $gold);
        $stmt->bindValue('workers', $workers);
        $stmt->bindValue('units', $units);
        $stmt->bindValue('archers', $archers);
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
    function runBattle($units1, $level1, $units2, $level2){
        // $units2['units'] = max(0, $units2['units'] - intval($units1['archers']*rand(3, 5)/10));
        // $units1['units'] = max(0, $units1['units'] - intval($units2['archers']*min(1, $units2['units']/10)*rand(4, 6)/10));
        // if($units2['units'] < 0) $units2['units'] = 0;
        // if($units1['units'] < 0) $units1['units'] = 0;
        //
        // $units1['archers'] = max(0, $units1['archers'] - intval($units2['units']*rand(7, 8)/10));
        //
        // while($units1['units'] > 0 && $units2['units'] > 0){
        //     $units1['units'] = max(0, $units1['units'] - 1+2*$units2['archers']*rand(3, 5)/10);
        //     $units2['units'] = max(0, $units2['units'] - 1+2*$units1['archers']*rand(3, 5)/10);
        // }
        // if($units1['archers'] > 0){
        //     $units2['units'] = max(0, $units2['units'] - $units1['archers']*2/3);
        //     $units1['archers'] = max(0, $units1['archers'] - $units2['units']*8/10);
        // }
        // while($units1['units'] + $units1['archers'] > 0 && $units2['units'] + $units2['archers'] > 0){
        //     $p1 = $units1['units']*1.1 + $units1['archers']*0.9;
        //     $p2 = $units2['units']*1.1 + $units2['archers']*0.9;
        //     $units1['units'] = max(0, $units1['units'] - $p2);
        //     $units2['units'] = max(0, $units2['units'] - $p1);
        //     $units1['archers'] = max(0, $units1['archers'] - $p2*$level2);
        //     $units2['archers'] = max(0, $units2['archers'] - $p1*$level1);
        // }
        // if($units1['units'] < 0) $units1['units'] = 0;
        // if($units2['units'] < 0) $units2['units'] = 0;
        $mn = min($units1['units'], $units2['units']);
        $units1['units'] -= $mn;
        $units2['units'] -= $mn;

        $mn = min($units1['archers'], $units2['archers']);
        $units1['archers'] -= $mn;
        $units2['archers'] -= $mn;

        $won = false;
        if($units2['units'] + $units2['archers'] == 0 && $units1['units'] + $units1['archers'] > 0){
            $won = true;
        }
        // var_dump($units1, $units2, $won);die();
        return ['attacker' => $units1, 'target' => $units2, 'won' => $won];
    }
    function sendReport($user_id, $city_id, $type, $user2name, $info, $time){
        $stmt = $this->db->prepare("INSERT INTO reports (user_id, city_id, title, content, time) VALUES(:uid, :cid, :title, :content, :time)");
        $stmt->bindValue('uid', $user_id);
        $stmt->bindValue('cid', $city_id);
        $stmt->bindValue('time', $time);
        if(in_array($type, ['won', 'lost', 'resisted', 'attacked'])){
            $u0 = $info[0]; //units init
            $u1 = $info[1]; // units final
            $html1 = '<p>Attacker:<br>';
            $html1 .='<img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> ';
            $html1 .='<span>'.$u0['attk']['units'].' -&gt; '.$u1['attk']['units'].'</span> <br>';
            $html1 .='<img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> ';
            $html1 .='<span>'.$u0['attk']['archers'].' -&gt; '.$u1['attk']['archers'].'</span> <br>';
            $html1 .='</p>';
            $html1 .= '<p>Defender:<br>';
            $html1 .='<img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> ';
            $html1 .='<span>'.$u0['target']['units'].' -&gt; '.$u1['target']['units'].'</span> <br>';
            $html1 .='<img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> ';
            $html1 .='<span>'.$u0['target']['archers'].' -&gt; '.$u1['target']['archers'].'</span> <br>';
            $html1 .='</p>';
            if($type == 'won'){
                $title = 'You won the attack against '.$user2name;
            }elseif($type == 'lost'){
                $title = 'You lost your attack on '.$user2name;
            }elseif($type == 'resisted'){
                $title = 'You resisted the attack from '.$user2name;
            }elseif($type == 'attacked'){
                $title = 'You have been attacked by '.$user2name;
            }
            $stmt->bindValue('title', $title);
            $stmt->bindValue('content', $html1);

            $stmt->execute();
            return true;
        }
    }
}
