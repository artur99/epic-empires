<?php
namespace Misc;

class SeoClass{
    protected $type = 'index';
    protected $meta = [];
    protected $og = [];
    function __construct($type = 0, $app = 0){
        if($type!==0) $this->type = $type;
        if($app!==0) $this->appPreInit($app);
        $this->preInit();
    }
    protected function appPreInit($app){
        $this->og['url'] = $app['request']->getUri();
    }
    protected function preInit(){
        $this->og['image:width'] = 1200;
        $this->og['image:height'] = 630;
        $this->og['image'] = a_link('img/cover.jpg');
    }
    public function auto(){
        $d = StaticSeoClass::get($this->type);
        foreach($d as $k=>$el){
            $this->set($k, $el);
        }
    }
    public function set($key, $val){
        if($key == 'title') return $this->setTitle($val);
        if($key == 'descr') return $this->setDescr($val);
        if($key == 'description') return $this->setDescr($val);
        if($key == 'img') return $this->setImg($val);
    }
    public function setTitle($val){
        $this->og['title'] = $val;
    }
    public function setDescr($val){
        $this->meta['description'] = $this->og['description'] = $val;
    }
    public function setImg($val){
        $this->og['image'] = $val;
    }
    public function getAll(){
        return [
            'meta' => $this->meta,
            'og' => $this->og
        ];
    }
}
