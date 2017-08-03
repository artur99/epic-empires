<?php

// Router list

$app->mount('/', new Controllers\IndexController());
$app->mount('/game', new Controllers\GameController());

$app->mount('/ajax/user', new Controllers\UserAjaxController());
$app->mount('/ajax/data', new Controllers\GameDataAjaxController());
