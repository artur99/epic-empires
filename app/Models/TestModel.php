<?php

namespace Models;

use Stringy\Stringy as S;
use PDO;

class TestModel extends BaseModel{
    public function search($string, $limit = 5){
        $string = S::create($string)->slugify('%')->__toString();
        $results = [];

        if(strlen(trim($string))>0){
            $stmt = $this->db->prepare("SELECT * FROM cities WHERE name LIKE :name ORDER BY CASE WHEN name LIKE :name2 THEN 1 ELSE 2 END ASC LIMIT :limit");
            $stmt->bindValue('name', '%'.$string.'%');
            $stmt->bindValue('name2', $string.'%');
            $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
        }
        return [
            'results' => $results
        ];
    }
    public function find($slug){
        $slug = S::create($slug)->slugify('-')->truncate(25)->__toString();

        if(strlen(trim($slug))>0){
            $slug = (string) $slug;
            $stmt = $this->db->prepare("SELECT * FROM cities WHERE slug = :slug LIMIT 1");
            $stmt->bindValue('slug', $slug);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        }
        return false;
    }
    public function getShops($oras_id){
        $id = intval($oras_id);
        $stmt = $this->db->prepare("SELECT * FROM magazine_fizice WHERE id_oras = :id ORDER BY romanesc DESC LIMIT 100");
        $stmt->bindValue('id', $oras_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $sp = -1;
        foreach($result as $i => $res){
            if($res['romanesc'] == '1'){
                $sp = $i;
            }else{
                break;
            }
        }
        return [
            'romanesti' => array_slice($result, 0, $sp+1),
            'straine' => array_slice($result, $sp+1, sizeof($result))
        ];
    }
    public function getShopsByCoord($lat, $lng, $size){
        $lng_l = (float)($lng - $size);
        $lng_r = (float)($lng + $size);
        $lat_l = (float)($lat - $size);
        $lat_r = (float)($lat + $size);

        return $this->getShopsByBorder($lng_l, $lng_r, $lat_l, $lat_r);
    }
    function getShopsByBorder($lng_l, $lng_r, $lat_l, $lat_r){
        $stmt = $this->db->prepare("SELECT * FROM magazine_fizice WHERE coord_lat > :lat_l AND coord_lat < :lat_r AND coord_lng > :lng_l AND coord_lng < :lng_r ORDER BY romanesc DESC LIMIT 100");
        $stmt->bindValue('lng_l', $lng_l);
        $stmt->bindValue('lng_r', $lng_r);
        $stmt->bindValue('lat_l', $lat_l);
        $stmt->bindValue('lat_r', $lat_r);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }
}
