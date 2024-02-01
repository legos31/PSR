<?php

use App\Services\UserService;
use Framework\Authentication\SessionAuthentication;
use Framework\Authentication\SessionAuthInterface;
use Framework\Event\EventDispatcher;
use Framework\http\Kernel;
use Framework\Http\Middleware\ExtractRouteInfo;
use Framework\Http\Middleware\RequestHandler;
use Framework\Http\Middleware\RequestHandlerInterface;
use Framework\Http\Middleware\RouterDispatch;
use Framework\Routing\Router;
use Framework\routing\RouterInterface;
use Framework\Session\SessionInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use Symfony\Component\Dotenv\Dotenv;


// Application parameters
$basePath = dirname(__DIR__);
$routes = include(BASE_PATH . '/routes/web.php');
$viewsPath = BASE_PATH . '/views';
$dbUrl = 'pdo-mysql://root:root@localhost:3306/fw_blond?charset=utf8mb4';

$container = new Container();
$container->delegate(
    new League\Container\ReflectionContainer()
);

$container->add('base-path', new StringArgument($basePath));

$container->add(RouterInterface::class, Router::class);
//$container->extend(RouterInterface::class)
//    ->addMethodCall('registerRoutes',['routes' => $routes]);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);
$container->addShared(EventDispatcher::class);

$container->add('APP_ENV', new StringArgument($_ENV['APP_ENV'] ?? 'local'));
$container->add('appEnv', Kernel::class);
$container->add(Kernel::class)
    ->addArguments([
        $container,
        RequestHandlerInterface::class,
        EventDispatcher::class
    ]);

$container->add('twig-factory', \Framework\Template\TwigFactory::class)
    ->addArguments([
        new StringArgument($viewsPath),
        SessionInterface::class,
        SessionAuthInterface::class
    ]);

$container->addShared('twig',function () use ($container) {
    return $container->get('twig-factory')->create();
});

$container->inflector(\Framework\controller\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(\Framework\dbal\ConnectionFactory::class)
    ->addArgument(new StringArgument($dbUrl));

$container->addShared(\Doctrine\DBAL\Connection::class, function () use ($container):\Doctrine\DBAL\Connection {
    return $container->get(\Framework\dbal\ConnectionFactory::class)->create();
});

$container->add(Framework\console\Kernel::class)
    ->addArgument($container)
    ->addArgument(\Framework\console\Application::class);

$container->add(\Framework\console\Application::class)
    ->addArgument($container);

$container->add('migrate', \Framework\console\commands\MigrateCommand::class)
    ->addArgument(\Doctrine\DBAL\Connection::class);

$container->addShared(SessionInterface::class, Framework\Session\Session::class);

$container->add(RouterDispatch::class)
    ->addArguments([
        RouterInterface::class,
        $container
    ]);

$container->add(SessionAuthInterface::class, SessionAuthentication::class)
    ->addArguments([
        UserService::class,
        SessionInterface::class,
    ]);
$container->add(ExtractRouteInfo::class)
    ->addArgument(new ArrayArgument($routes));


return $container;