<?php

$router->get('/', function () use ($router) {
    return 'HELLO WORLD!!!';
});

$router->group([
    'prefix' => 'api',
], function() use($router) {
    $router->get('track/location_relay', 'LocationRelayController@index');
    $router->get('track/location_relay/latest', 'LocationRelayController@latest_loc_relay');
});