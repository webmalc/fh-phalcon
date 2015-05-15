<?php
/**
 * Global assets
 */

// CDN css files
$di->get('assets')
    ->collection('cdnCss')
    ->setLocal(false)
    ->addCss('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css')
    ->addCss('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css')
    ->addCss('//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css')
;

// App css files
$di->get('assets')
    ->collection('css')
    ->setTargetPath('css/fh.css')
    ->setTargetUri('css/fh.css')
    ->addCss('css/000-bootswatch.paper.min.css')
    ->addCss('js/bower_components/angular-xeditable/dist/css/xeditable.css')
    ->addCss('js/bower_components/ng-tags-input/ng-tags-input.min.css')
    ->addCss('js/bower_components/ng-tags-input/ng-tags-input.bootstrap.min.css')
    ->addCss('css/001-app.css')
    ->addCss('css/002-animation.css')
    ->addCss('css/003-navbar.css')
    ->join(true)
    ->addFilter(new \Phalcon\Assets\Filters\Cssmin())
;
// CDN js files
$di->get('assets')
    ->collection('cdnJs')
    ->setLocal(false)
    ->addJs('//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')
    ->addJs('//ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js')
    ->addJs('//ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular-animate.min.js')
    ->addJs('//ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular-resource.min.js')
    ->addJs('//ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular-route.min.js')
    ->addJs('//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.12.0/ui-bootstrap-tpls.min.js')
;
// Project js files
$di->get('assets')
    ->collection('js')
    ->setTargetPath('js/fh.js')
    ->setTargetUri('js/fh.js')
    ->addJs('js/bower_components/autofill-event/src/autofill-event.js')
    ->addJs('js/bower_components/angular-xeditable/dist/js/xeditable.min.js')
    ->addJs('js/bower_components/angular-smart-table/dist/smart-table.min.js')
    ->addJs('js/bower_components/ng-tags-input/ng-tags-input.min.js')
    ->addJs('js/app.js')
    ->addJs('js/config.js')
    ->addJs('js/services/user.js')
    ->addJs('js/services/finances.js')
    ->addJs('js/controllers/main.js')
    ->addJs('js/controllers/login.js')
    ->addJs('js/controllers/profile.js')
    ->addJs('js/controllers/user.js')
    ->addJs('js/controllers/finances.js')    
    ->join(true)
    ->addFilter(new \Phalcon\Assets\Filters\Jsmin())
;