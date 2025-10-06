<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use App\Controller\VeiculoController;
use App\Repository\VeiculoRepository;
use App\Controller\DashboardController;
use App\Controller\MotoristaController;
use App\Repository\MotoristaRepository;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set('db', function () {
    $dsn = 'mysql:host=localhost;dbname=scav;charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
         return new PDO($dsn, 'root', '');
    } catch (\PDOException $e) {
         throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
});

$container->set('view', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});

$container->set(VeiculoRepository::class, function (Container $c) {
    return new VeiculoRepository($c->get('db'));
});

$container->set(VeiculoController::class, function (Container $c) {
    return new VeiculoController($c->get('view'), $c->get(VeiculoRepository::class));
});

$container->set(DashboardController::class, function (Container $c) {
    return new DashboardController($c->get('view'));
});

$container->set(MotoristaRepository::class, function (Container $c) {
    return new MotoristaRepository($c->get('db'));
});

$container->set(MotoristaController::class, function (Container $c) {
    return new MotoristaController($c->get('view'), $c->get(MotoristaRepository::class));
});

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->setBasePath('/scav/public');

$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->addErrorMiddleware(true, true, true);

$app->run();
