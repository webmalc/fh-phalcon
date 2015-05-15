<?php
namespace FH\Controllers;

/**
 * Main controller class
 */
class MainController extends ControllerBase
{
    /**
     * Index action
     * @return \Phalcon\Http\Response
     */
    public function modalAction()
    {
        $this->onlyAjax();
    }
}