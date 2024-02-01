<?php
ini_set('memory_limit', -1);
set_time_limit(1800);
define('BASE_PATH', dirname(__DIR__));

use Framework\Http\Request;
use Symfony\Component\Dotenv\Dotenv;

require_once BASE_PATH.'/vendor/autoload.php';
$dotenv = new Dotenv();
$dotenv->load(BASE_PATH.'/.env');

$request = Request::createFromGlobals();

/** @var \League\Container\Container $container */
$container = require BASE_PATH .'/config/services.php';
require_once BASE_PATH.'/bootstrap/bootstrap.php';

//$eventDispatcher = $container->get(\Framework\Event\EventDispatcher::class);
//$eventDispatcher->addListener(
//    Framework\Http\Events\ResponseEvent::class, new App\Listeners\ContentLengthListener()
//)->addListener(
//    \Framework\Dbal\Event\EntityPersist::class, new \App\Listeners\HandleEntityListener()
//);


$kernel = $container->get(\Framework\http\Kernel::class);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);