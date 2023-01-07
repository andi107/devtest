<?php

$router->get('/', function () use ($router) {
    return 'HELLO WORLD!!!';
});

$router->group([
    'prefix' => 'api',
], function() use($router) {
    $router->get('track/location_relay', 'LocationRelayController@index');
    $router->get('track/location_relay/latest', 'LocationRelayController@latest_loc_relay');
    $router->get('track/location_relay/deviceslist', 'LocationRelayController@deviceList');
    $router->get('track/location_relay/logs', 'LocationRelayController@trackinglogs');

    $router->get('track/location_relay/chart', 'LocationRelayController@dasboardChart');

    $router->get('track/ignition', 'LocationRelayController@ignitionData');
    $router->get('track/location_relay/geodeclare', 'LocationRelayController@geoDeclareList');
});