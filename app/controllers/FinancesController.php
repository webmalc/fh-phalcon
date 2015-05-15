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

    /**
     * Save user
     * @return \Phalcon\Http\Response
     */
    public function tagsAction()
    {
        $data = $this->jsonRequest('post');
        if (empty($data['query'])) {
            return $this->error404();
        }

        return $this->jsonResponse([
            ['text' => 'sdfg1'], ['text' => 'sdfg2'] , ['text' => 'sdfg3']
        ]);
    }
}