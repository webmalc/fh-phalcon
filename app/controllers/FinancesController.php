<?php
namespace FH\Controllers;

use FH\Models\Finances;
use FH\Models\FinancesTag;

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
     * Finance new action
     * @return Response
     */
    public function newAction()
    {
        $data = $this->jsonRequest('post');

        if (empty($data)) {
            return $this->error404();
        }

        $transaction = new Finances();
        $transaction->setData($data);

        if (isset($data['user']['id'])) {
            $transaction->user_id = $data['user']['id'];
        } else {
            $transaction->user = $this->di->get('auth')->getUser();
        }

        //tags
        if(isset($data['tags'])) {
            $tags = [];
            foreach ($data['tags'] as $key => $tagData) {
                $tags[$key] = new FinancesTag();
                $tags[$key]->title = $tagData['text'];
            }
            $transaction->tags = $tags;
        }

        if ($transaction->save()) {

            return $this->jsonResponse([
                'success' => true,
                'message' => 'The transaction was successfully created! '
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => implode('; ', $transaction->getMessages())
            ]);
        }
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

        $result = [];
        $tags = new FinancesTag();

        foreach($tags->getDistinct($data['query']) as $tag) {
            $result[] = ['text' => $tag];
        }

        return $this->jsonResponse($result);
    }
}