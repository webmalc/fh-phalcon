<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    [
        'FH\Controllers' => $config->application->controllersDir,
        'FH\Models' => $config->application->modelsDir,
        'FH\Lib' => $config->application->libDir,
        'FH\Validators' => $config->application->validatorsDir
    ]
)->register();
