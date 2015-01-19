<?php

mb_internal_encoding('UTF-8');

try {

    /**
     * Read the configuration
     */
    $config = new \Phalcon\Config\Adapter\Ini(__DIR__ . "/../app/config/config.ini");

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

} catch (Phalcon\Exception $e) {
    if($application->getDI()->get('config')->environment->type != 'prod') {
        var_dump($e);
    }
} catch (PDOException $e) {
    if($application->getDI()->get('config')->environment->type != 'prod') {
        var_dump($e);
    }
}
