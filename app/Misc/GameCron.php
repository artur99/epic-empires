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
                $max = $maxes[$task['city_level']];
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
                if($task['param'] == 'center')
                    $this->buildingLvlUpdate($task['city_id'], 'level');
                $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                $stmt->bindValue('id', $task['id']);
                $stmt->execute();
            }elseif($task['type'] == 'attack'){
                $ta = $this->getCityInfo($task['city_id']);
                $tq = $this->getCityInfo($task['target']);
                $unz = @json_decode($task['param']);

                $units1 = [
                    'units' => intval(isset($unz->units)?$unz->units:0),
                    'archers' => intval(isset($unz->archers)?$unz->archers:0)
                ];
                $units2 = [
                    'units' => intval($tq['units']),
                    'archers' => intval($tq['archers'])
                ];
                $battle = $this->runBattle($units1, $task['city_level'], $units2, $tq['level']);
                $units0 = [$units1, $units2];
                $p1_lost['units'] = -max(0, $units0[0]['units'] - max(0, $battle[0]['units']));
                $p1_lost['archers'] = -max(0, $units0[0]['archers'] - max(0, $battle[0]['archers']));
                $p2_lost['units'] = -max(0, $units0[1]['units'] - max(0, $battle[1]['units']));
                $p2_lost['archers'] = -max(0, $units0[1]['archers'] - max(0, $battle[1]['archers']));

                $food = $tq['r_food'];
                $wood = $tq['r_wood'];
                $gold = $tq['r_gold'];
                $prd = ['food' => $food, 'wood' => $wood, 'gold' => $gold];
                if($battle[2] == true){
                    //He won the battle

                    $max = $maxes[$task['city_level']];
                    $this->resUpdate($tq['id'], $food, $wood, $gold, 0, 0, $max, $battle[0]['units'], $battle[0]['archers']);
                    $this->resUpdate($ta['id'], -$food, -$wood, -$gold, 0, 0, 99999, $p2_lost['units'], $p2_lost['archers']);
                    $this->sendReport($ta['user_id'], $ta['id'], 'won', $tq['username'], [$units0, $battle, $prd], $task['time_e']);
                    $this->sendReport($tq['user_id'], $tq['id'], 'attacked', $ta['username'], [$units0, $battle, $prd], $task['time_e']);

                    $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
                    $stmt->bindValue('id', $task['id']);
                    $stmt->execute();
                }else{
                    $this->resUpdate($tq['id'], 0, 0, 0, 0, 0, 99999, $p1_lost['units'], $p1_lost['archers']);
                    // $this->resUpdate($ta['id'], 0, 0, 0, 0, 0, 99999, $p2_lost['units'], $p2_lost['archers']);

                    $this->sendReport($ta['user_id'], $ta['id'], 'lost', $tq['username'], [$units0, $battle], $task['time_e']);
                    $this->sendReport($tq['user_id'], $tq['id'], 'resisted', $ta['username'], [$units0, $battle], $task['time_e']);
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
        $points = intval($points);
        $workers = intval($workers);
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
        $units1['units'] -= min($units1['units'], $units2['units']);
        $units2['units'] -= min($units1['units'], $units2['units']);

        $units1['archers'] -= min($units1['archers'], $units2['archers']);
        $units2['archers'] -= min($units1['archers'], $units2['archers']);

        $won = false;
        if($units2['units'] + $units2['archers'] == 0 && $units1['units'] + $units1['archers'] > 0){
            $won = true;
        }
        // var_dump($units1, $units2, $won);die();
        return [$units1, $units2, $won];
    }
    function sendReport($user_id, $city_id, $type, $user2name, $info, $time){
        $stmt = $this->db->prepare("INSERT INTO reports (user_id, city_id, title, content, time) VALUES(:uid, :cid, :title, :content, :time)");
        $stmt->bindValue('uid', $user_id);
        $stmt->bindValue('cid', $city_id);
        $stmt->bindValue('time', $time);
        if(in_array($type, ['won', 'lost', 'resisted', 'attacked'])){
            $u0 = $info[0];
            $u1 = $info[1];
            $html1 = '<p>Attacker:<br>';
            $html1 .='<img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> ';
            $html1 .='<span>'.$u0[0]['units'].' -&gt; '.$u1[0]['units'].'</span> <br>';
            $html1 .='<img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> ';
            $html1 .='<span>'.$u0[0]['archers'].' -&gt; '.$u1[0]['archers'].'</span> <br>';
            $html1 .='</p>';
            $html1 .= '<p>Defender:<br>';
            $html1 .='<img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> ';
            $html1 .='<span>'.$u0[1]['units'].' -&gt; '.$u1[1]['units'].'</span> <br>';
            $html1 .='<img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> ';
            $html1 .='<span>'.$u0[1]['archers'].' -&gt; '.$u1[1]['archers'].'</span> <br>';
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
