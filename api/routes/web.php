<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'api',
], function() use($router) {
    $router->get('track/status', 'TrackController@status');
    $router->get('track/map', 'TrackController@map');
    $router->get('track/log', 'TrackController@log');
    $router->get('track/dummy', 'TrackController@dummyTrack');

    $router->get('dummy/save', 'RcvLogController@save');
});