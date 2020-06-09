<?php

namespace App\Controllers;

use \App\Auth;
use \Core\View;
use \App\Models\Item;
use \App\Models\Gallery;
use \App\Models\User;
use \App\Crypto;
use \App\Flash;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Items extends Authenticated
{

    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    protected function before()
    {
        $this->requireLogin();
        $this->user = Auth::getUser();
        $this->user->phone = Crypto::decrypt($this->user->phone); //MOVE TO METHOD WHICH REQUIRE IT
    }

    /**
     * Items index
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Items/index.html');
    }

    /**
     * Add a new item
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Item/new.html', ['user' => $this->user]);
    }

    public function newImgAction() {
        View::renderTemplate('Item/newImg.html');
    }

    /**
     * create new item 
     *
     * @return void
     */
    public function createAction()
    {
        $item = new Item($_POST); //create new object of App\Model\Item
        if ($item->save($this->user->id, $_FILES['img'])) {
            Flash::addMessage('Обьявление добавлено успешно! :)');
            echo "REDIRECT TO HOME";
            //$this->redirect('/');
        } else {
            Flash::addMessage('Произошла ошибка, попробуйте еще раз.', Flash::WARNING);
            View::renderTemplate('Item/new.html', ['user' => $this->user, 'item' => $item]);
        }
    }

    public function showAction() {
        if(isset($_GET['id'])) {
            $item_ = new Item;
            $item = $item_->findByID($_GET['id']); //ADD ERROR PARCE
            $user_ = new User;
            $userContactInfo = $user_->getContactInfo($item->user_id);
            $userContactInfo->phone = Crypto::decrypt($userContactInfo->phone);
            View::renderTemplate('Item/show.html', ['item' => $item, 
                                                    'current_user_id' => $this->user->id, 
                                                    'contact_info' => $userContactInfo]);
        }
        else {
            $this->redirect('/');
        }
    }

}
