<?php

namespace Models;

use Stringy\Stringy as S;
use PDO;
use Misc\StaticData;

class UserModel extends BaseModel{
    function in(){
        $has = $this->session->has('user');
        if(!$has){
            return false;
        }
        $user = $this->session->get('user');
        if(!$user){
            return false;
        }
        return true;
    }
    function info(){
        if($this->in())
            return $this->session->get('user');
        return false;
    }
    function pwEncode($pw){
        return substr(hash('sha512', $pw), 0, 50);
    }
    function emExists($em){
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM users WHERE email = :em LIMIT 1");
        $stmt->bindValue('em', $em);
        $stmt->execute();
        $results = $stmt->fetch();
        if($results['COUNT(1)'] > 0)
            return true;
        return false;
    }
    function unExists($username){
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM users WHERE username = :user LIMIT 1");
        $stmt->bindValue('user', $username);
        $stmt->execute();
        $results = $stmt->fetch();
        if($results['COUNT(1)'] > 0)
            return true;
        return false;
    }
    function signup($un, $em, $pw, $pw2){
        $err = false;
        $em = trim($em);
        $un = trim($un);
        $pw = trim($pw);
        $pw2 = trim($pw2);
        if(strlen($un) < 3){
            $err = 'The username is too short';
        }elseif(strlen($un) > 32){
            $err = 'The username is too long';
        }elseif(!filter_var($em, FILTER_VALIDATE_EMAIL) || strlen($em) > 50){
            $err = 'The email is invalid';
        }elseif($this->emExists($em)){
            $err = 'There is already an account registered with this email address';
        }elseif($this->unExists($un)){
            $err = 'The username is taken';
        }elseif(strlen($pw) < 6){
            $err = 'The password is too short';
        }elseif($pw != $pw2){
            $err = 'Passwords don\'t match';
        }else{
            $pw = (string) $this->pwEncode($pw);
            $stmt = $this->db->prepare("INSERT INTO users(username, email, password, reg_tm, log_tm, reg_ip, log_ip) VALUES(:un, :em, :pw, :tm, :tm, :ip, :ip)");
            $stmt->bindValue('un', $un);
            $stmt->bindValue('em', $em);
            $stmt->bindValue('pw', $pw);
            $stmt->bindValue('tm', time());
            $stmt->bindValue('ip', isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:NULL);
            $stmt->execute();
            $id = $this->db->lastInsertId();

            $this->initializeUserData($id);

            $this->loginSet($id, $un, $em);

        }
        return [
            'type' => $err?'error':'success',
            'text' => $err?$err:'Successfuly signed up'
        ];
    }
    function login($un, $pw){
        $err = false;
        $un = trim($un);
        $pw = trim($pw);
        if(strlen($un) > 32){
            $err = 'The username is invalid';
        }elseif(strlen($pw) < 6){
            $err = 'The password is wrong';
        }else{
            $pw = (string) $this->pwEncode($pw);
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :un AND password = :pw LIMIT 1");
            $stmt->bindValue('un', $un);
            $stmt->bindValue('pw', $pw);
            $stmt->execute();
            $r = $stmt->fetch();
            if(!isset($r['id'])){
                $err = 'Wrong username or password';
            }else{
                $stmt = $this->db->prepare("UPDATE users SET log_tm = :tm, log_ip = :ip");
                $stmt->bindValue('tm', time());
                $stmt->bindValue('ip', isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:NULL);
                $stmt->execute();
                $this->loginSet($r['id'], $r['username'], $r['email']);
            }
        }
        return [
            'type' => $err?'error':'success',
            'text' => $err?$err:'Logged in successfuly!'
        ];
    }
    function loginSet($id, $un, $em){

        $data_arr = [
            'id' => $id,
            'username' => $un,
            'email' => $em
        ];
        if($id == 1){
            $data_arr['admin'] = 1;
        }
        $this->session->set('user', $data_arr);
        return true;
    }
    function signout(){
        $this->session->set('user', null);
        $this->session->remove('user');
        return [
            'type' => 'success',
            'text' => null
        ];
    }
    function initializeUserData($user_id){
        $helper = new HelperModel($this->db);
        $user_id = intval($user_id);

        $last_x = intval($helper->getDbVar('last_x'));
        $last_y = intval($helper->getDbVar('last_y'));
        $map_size = intval($helper->getDbVar('map_size'));

        $new_x = 1;
        $new_y = 1;
        if($last_x != 0 || $last_y != 0){
            if($last_x == 1){
                $diag_nr = $last_x + $last_y + 1;
                $new_y = 1;
                $new_x = $diag_nr - 1;
            }else{
                $new_x = $last_x - 1;
                $new_y = $last_y + 1;
            }
        }
        if($new_x > $map_size || $new_y > $map_size){
            return false;
        }
        $stmt = $this->db->prepare('INSERT INTO cities (user_id, loc_x, loc_y) VALUES(:uid, :loc_x, :loc_y)');
        $stmt->bindValue('uid', $user_id);
        $stmt->bindValue('loc_x', $new_x);
        $stmt->bindValue('loc_y', $new_y);
        $stmt->execute();

        $helper->setDbVar('last_x', $new_x);
        $helper->setDbVar('last_y', $new_y);

        return true;
    }

    function getData(){
        if(!$this->in()) return $this->err();
        $uid = $this->info()['id'];
        $data = [];
        $data['cities'] = $this->getUserCities($uid);

        return $data;
    }

    private function getUserCities($user_id){
        $user_id = intval($user_id);
        $stmt = $this->db->prepare("SELECT * FROM cities WHERE user_id = :uid");
        $stmt->bindValue('uid', $user_id);
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
