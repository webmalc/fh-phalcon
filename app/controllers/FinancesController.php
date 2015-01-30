<?php
namespace FH\Controllers;

/**
 * Finances controller class
 */
class FinancesController extends ControllerBase
{
    /**
     * Index action
     * @return \Phalcon\Http\Response
     */
    public function indexAction()
    {
        $this->onlyAjax();
    }

}