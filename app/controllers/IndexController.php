<?php
namespace FH\Controllers;

use FH\Models\User;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $user = User::find(4);
        $user->delete();
    }
}

