<?php
mb_internal_encoding('UTF-8');

try {

    /**
     * Read the configuration
     */
    $config = new \Phalcon\Config\Adapter\Ini(__DIR__ . "/../app/config/config.ini");

    /**
     * Errors
     */
    if (!$config->environment->type != 'prod') {
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set("display_errors", 1);
    }

    /**
     * Include composer autoload
     */
    require __DIR__ . '/../vendor/autoload.php';

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    /**
     * Include assets
     */
    include __DIR__ . "/../app/config/assets.php";

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    if($application->getDI()->get('config')->environment->type != 'prod') {
        $response =  $application->getDI()->get('response');
        $response->setStatusCode(500 , $e->getMessage());
        $response->send();
    } else {
        $application->getDI()->get('logger')->log($e->getMessage(), \Phalcon\Logger::ERROR);
        $response =  $application->getDI()->get('response');
        $response->setStatusCode(500 , "Internal Server Error");
        $response->setContent("500 Internal Server Error");
        $response->send();
    }
}
