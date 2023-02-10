<?php

$router->get('/', function () use ($router) {
    return 'HELLO WORLD!!!';
});

$router->group([
    'prefix' => 'api',
], function() use($router) {
    $router->group([
        'prefix' => 'devices',
    ], function() use($router) {
        $router->get('/', 'Devices\DeviceController@index');
        $router->post('create', 'Devices\DeviceController@create');
        $router->post('update', 'Devices\DeviceController@update');
        $router->get('list', 'Devices\DeviceController@list');
    });

    $router->group([
        'prefix' => 'debugging',
    ], function() use($router) {
        $router->get('routelist', 'Maps\DebuggingRouteController@list');
    });
    $router->get('track/location_relay', 'LocationRelayController@index');
    $router->get('track/location_relay/latest', 'LocationRelayController@latest_loc_relay');
    $router->get('track/location_relay/deviceslist', 'LocationRelayController@deviceList');
    $router->get('track/location_relay/logs', 'LocationRelayController@trackinglogs');

    $router->get('track/location_relay/chart', 'LocationRelayController@dasboardChart');

    $router->get('track/ignition', 'LocationRelayController@ignitionData');
    $router->get('track/location_relay/geodeclare', 'LocationRelayController@geoDeclareList');
    $router->get('track/location_relay/routeInGeo', 'LocationRelayController@routeInGeo');
    $router->get('track/location_relay/geoDeclareLts', 'LocationRelayController@geoDeclareLts');
    $router->get('track/location_relay/current_run', 'LocationRelayController@currentRun');
    
    
});