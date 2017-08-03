<?php

namespace Models;

class HelperModel extends BaseModel{
    function getDbVar($id = null){
        if(!$id) return false;
        $stmt = $this->db->prepare("SELECT id, value FROM vars WHERE id = :id LIMIT 1");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $res = $stmt->fetch();

        return isset($res['value']) ? $res['value'] : false;
    }
    function setDbVar($id = null, $val = null){
        if(!$id) return false;
        $stmt = $this->db->prepare("UPDATE vars SET value = :val WHERE id = :id LIMIT 1");
        $stmt->bindValue('id', $id);
        $stmt->bindValue('val', $val);
        $stmt->execute();

        return true;
    }
}
