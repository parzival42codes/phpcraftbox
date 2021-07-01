<?php

ini_set('memory_limit',
        '512M');
ini_set("max_execution_time",
        720);

require(dirname(__FILE__) . '/Library/config.inc.php');

Config::setDatabase();
ContainerFactoryLanguage::setCore();

if (ContainerFactorySession::check()) {
    if (!ContainerFactorySession::get('/user/id')) {
        ContainerFactorySession::destroy();
    }
    else {
        Container::getInstance('ContainerFactoryUser',
                               ContainerFactorySession::get('/user/id'));
    }
}
else {
    Container::getInstance('ContainerFactoryUser',
                           0);
}

$container = Container::DIC([
                                '/User'   => new ContainerFactoryUser((int)ContainerFactorySession::get('/user/id') ?? 0),
                                '/Config' => new Config(),
                                '/Router' => new ContainerFactoryRouter(Config::get('/server/http/path')),
                                '/Page' => new ContainerIndexPage(),
                            ]);

/** @var ContainerFactoryRouter $router */
$router = Container::getInstance('ContainerFactoryRouter');

if (Config::get('/server/http/path') !== '') {
    $router->analyzeUrl(Config::get('/server/http/path'));
}

ContainerFactoryUserConfig::setDatabase();


switch (Container::getInstance('ContainerFactoryRouter')
                 ->getTarget()) {
    default:
    case 'index':
        CoreIndex::execute();
        exit;
        break;
    case 'ajax':
        /** @var ContainerFactoryRouter $router */
        $classPhp = $router->getApplication() . '_ajax';
        Container::get($classPhp);
        break;
    case 'free':
        $class  = $router->getApplication() . '_free';
        $object = Container::get($class);
//
//        \Event::trigger('allExecFree',
//                        'allExecFree',
//                        'allExecFree',
//                        null,
//                        $scope);
//        \Event::trigger('allExec',
//                        'allExec',
//                        'allExec',
//                        null,
//                        $scope);
        break;
}
