<?php

namespace Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class IndexController implements ControllerProviderInterface{
    public function connect(Application $app){
        $indexController = $app['controllers_factory'];
        $indexController->get('/', [$this, 'index']);
        return $indexController;
    }
    public function index(Application $app){
        $seo = new \Misc\SeoClass();
        $seo->set('title', 'Epic Empires - A brand new MMO');
        $seo->set('descr', 'Play a brand new MMO on Epic Empires, just taken off from the game developers\' desk.');

        return $app['twig']->render('index.twig', [
            'meta' => $seo->getAll()
        ]);
    }
}
