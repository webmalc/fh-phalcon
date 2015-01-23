<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Postgresql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Router;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Config service
 */
$di->set('config', $config);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Registering a router
 */
$di->set('router', function () {
    $router = new Router();
    $router->setDefaultNamespace('FH\Controllers');

    return $router;
});

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                'compileAlways' => ($config->environment->type == 'dev') ? true : false,
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Setting up logger
 */
$di->set('logger', function () use ($di) {
    $logger = new Phalcon\Logger\Adapter\File($di->get('config')->application->logPath);
    return $logger;
}, true);

/**
 * Setting up helper
 */
$di->set('helper', function () {
    return new FH\Lib\Helper();
}, true);

/**
 * Setting up acl
 */
$di->set('acl', function() use ($di) {
    $config = $di->get('config')->acl;
    return new FH\Lib\Acl($config->path, $config->apc);
}, true);

/**
 * Setting up mail
 */
$di->set('mail', function() {
    return new FH\Lib\Mail();
}, true);

/**
 * Setting up auth
 */
$di->set('auth', function () {
    return new FH\Lib\Auth();
}, true);

/**
 * Setting up crypt
 */
$di->set('crypt', function() use ($di) {
    $crypt = new Phalcon\Crypt();
    $crypt->setKey($di->get('config')->environment->token);

    return $crypt;
});