<?php
use Framework\console\KernelConsole;

define('BASE_PATH', dirname(__FILE__));

require_once BASE_PATH.'/vendor/autoload.php';

/** @var \League\Container\Container $container */
$container = require BASE_PATH .'/config/services.php';

$kernel = $container->get(Framework\console\Kernel::class);

$status = $kernel->handle();

exit($status);