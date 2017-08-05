<?php

use Silex\Provider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Yaml\Yaml;
use Stringy\Stringy as S;
use Models\UserModel;


$app['conf.path'] = dirname(dirname($_SERVER['SCRIPT_FILENAME']));
$app['twig.path'] = $app['conf.path'].'/app/Templates';
$app['twig.assets'] = '/assets/';
$app['request'] = function() use($app) {
    return $app['request_stack']->getCurrentRequest();
};
$app['conf.url'] = function($app)use($app){
    return $app['request']->getScheme() . '://' . $app['request']->getHttpHost() . $app['request']->getBasePath();
};
$app['conf.url_path'] = function($app){
    return $app['request']->getBasePath();
};
$config = Yaml::parse(@file_get_contents($app['conf.path']."/app/conf.yaml"));
if(!$config){
    throw new \Exception('Config file not defined');
}

foreach($config as $k=>$v){
    $app[$k]=$v;
}
$app['debug'] = true;
unset($config);
date_default_timezone_set($app['conf.timezone']);

$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'host' => $app['conf.db.host'],
    'user' => $app['conf.db.user'],
    'password' => $app['conf.db.pass'],
    'dbname' => $app['conf.db.name'],
    'charset'   => 'utf8'
);

// --------------------------------------- REBUILD structure here, broo


function g_link($link){
    global $app;
    if(strpos($link, '://') !== false) return $link;
    return $app['conf.url'].'/'.ltrim($link, '/');
}
function a_link($asset){
    global $app;
    if(strpos($asset, '://') !== false) return $asset;
    return $app['conf.url'].$app['twig.assets'].ltrim($asset, '/');
}
function a_image($loc){
    global $app;
    if(substr($loc, 0, 7) == 'http://' || substr($loc, 0, 8) == 'https://'){
        $link = $loc;
    }else{
        if(substr($loc, 0, 1) == '/' || substr($loc, 0, 2) == './' || substr($loc, 0, 2) == '..')
            $link = $loc;
        else{
            $link = $app['conf.url_path'].$app['twig.assets'].ltrim('img/'.$loc, '/');
        }
    }
    return $link;
}

function global_patches($app){
    global $fb;
    if(isset($app['conf.facebook.use']) && $app['conf.facebook.use']){
        $fb = new Facebook\Facebook(array(
          'app_id'  => $app['conf.facebook.app_id'],
          'app_secret' => $app['conf.facebook.app_secret']
        ));
    }else{
        $fb = null;
    }
}

$app['csrf'] = function () {
    return new CsrfTokenManager();
};
$app['twig'] = $app->extend('twig', function($twig,$app){
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    $twig->addFunction(new Twig_SimpleFunction('asset', function ($asset)use($app){
        if(strpos($asset, '://') !== false) return $asset;
        return $app['conf.url'].$app['twig.assets'].ltrim($asset, '/');
    }));
    $twig->addFunction(new Twig_SimpleFunction('user', function($what)use($app){
        return $app['user']->data($what);
    }));
    $twig->addFunction(new Twig_SimpleFunction('l', function($what)use($app){
        if(strpos($what, '://') !== false) return $what;
        return $app['conf.url'].'/'.ltrim($what, '/');
    }));
    $twig->addFunction(new Twig_SimpleFunction('csrftoken', function($id)use($app){
        return $app['csrf']->getToken($id)->__tostring();
    }));
    $twig->addFunction(new Twig_SimpleFunction('mact', function($id)use($app){
        $d1 = explode('/', ltrim($id, '/'));
        $d2 = explode('/', ltrim($app['request']->getPathInfo(), '/'));
        $cond = isset($d1[0], $d2[0]) && !empty($d1[0]) && $d1[0]==$d2[0];
        return $id == $app['request']->getPathInfo() || $cond ?' active':'';
    }));
    $twig->addFunction(new Twig_SimpleFunction('mact2', function($id)use($app){
        $d1 = explode('/', ltrim($id, '/'));
        $d2 = explode('/', ltrim($app['request']->getPathInfo(), '/'));
        $cond = isset($d1[0], $d2[0]) && !empty($d1[0]) && $d1[0]==$d2[0];
        return $id == $app['request']->getPathInfo() || $cond ?'class="active"':'';
    }));
    $twig->addFunction(new Twig_SimpleFunction('img', function($loc)use($app){
        return a_image($loc);
    }));
    $twig->addFunction(new Twig_SimpleFunction('gen_bgcss', function($loc)use($app){
        $link = a_image($loc);
        return 'background-image: url(\''.$link.'\')';
    }));
    $twig->addFilter(new Twig_SimpleFilter('slugify', function($text){
        $s = S::create($text);
        return $s->slugify();
    }));
    $twig->addFilter(new Twig_SimpleFilter('shorten', function($text){
        return Misc\MiscClass::shorten($text);
    }));
    return $twig;
});
$app['user'] = function() use ($app) {
    return new UserModel($app['db'], $app['session']);
};

$app['executers'] = function() use ($app) {
    return [
        'user' => new \DaySplit\Executers\UserExecuter($app['db']),
    ];
};


$app->before(function ($request)use($app) {
    $a = global_patches($app);
    $request->getSession()->start();
    $rm = new RequestMatcher();
    $rm->matchPath("/ajax/.*");
    if($rm->matches($request)){
        if($app['csrf']->getToken('main')->__tostring() != $request->get('csrftoken')){
            return new JsonResponse(['type'=>'error','token_error'=>1,'text'=>'Invalid token']);
        }
    }
});
