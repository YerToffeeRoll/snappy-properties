<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotEnv = new Dotenv();
$dotEnv->load(__DIR__.'/../.env');

$request = Request::CreateFromGlobals();

$kernel = new Kernel();
$response = $kernel->handle($request);
$response->send();
