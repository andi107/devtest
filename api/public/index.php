<?php
$app = require __DIR__.'/../bootstrap/app.php';


// $app->run();
$request = Illuminate\Http\Request::capture();
$app->run($request);