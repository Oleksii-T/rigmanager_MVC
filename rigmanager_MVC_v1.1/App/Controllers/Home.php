<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Item;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * get all items, show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $item = new Item;
        $items = $item->getAll();
        View::renderTemplate('Home/index.html', ['items_list' => $items]);
    }
    
}