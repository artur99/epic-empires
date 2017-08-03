<?php
namespace Misc;

class StaticSeoClass{
    function get($what){
        global $app;
        $res = [];
        if($what == 'home'){
            $res['title'] = $app['conf.title'];
        }
        return $res;
    }
}
