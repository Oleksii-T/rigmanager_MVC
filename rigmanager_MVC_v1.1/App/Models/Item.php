<?php

namespace App\Models;

use PDO;
use \Core\View;



/**
 * User model
 *
 * PHP version 7.0
 */
class Item extends \Core\Model
{
    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Save the item model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save($userID, $file)
    {
        //$this->validate(); //server side validation

        if (empty($this->errors)) {
            $sql = 'INSERT INTO items (user_id, title, tag, description)
            VALUES (:user_id, :title, :tag, :description)';
            
            $db = static::getDB();
            /*
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $userID, PDO::PARAM_STR);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':tag', $this->tag, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->desc, PDO::PARAM_STR);
            //Time stamp is added automaticatly...
            */
            if ( $file['name'][0] != '' ) {
                //return $stmt->execute() && saveImg($file);
                return $this->saveImg($userID,$file,$db);
            } else {
                //return $stmt->execute();
                return true;
            }
            
        }
        return false;
    }
    
    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    private function validate()
    {
        // title
        if (strlen($this->title) < 10) {
            $this->errors[] = 'Заголовок: Минимум 10 символов.';
        }
        if (strlen($this->title) > 70) {
            $this->errors[] = 'Заголовок: Максимум 70 символов.';
        }
        
        // descrription
        if (strlen($this->desc) < 10) {
            $this->errors[] = 'Описание: Минимум 10 символов.';
        }
        if (strlen($this->title) > 255) {
            $this->errors[] = 'Описание: Максимум 70 символов.';
        }
    }
    
    private function saveImg($userID, $file, $db)
    {
        
        $targetDir = "media/$userID/";
        if ( !is_dir($targetDir) ) {
            if ( !mkdir($targetDir) ) {
                $this->errors[] = 'Sorry, there was an error uploading your file.';
                return false;
            }
        }
            
        $this->validateImg($file);
        if ( empty($this->errors) ) {

            $i = 1;//loop through each submited image and upload it
            foreach($file['name'] as $key=>$val){
                $image_name = $file['name'][$key];
                $tmp_name   = $file['tmp_name'][$key];
                $size       = $file['size'][$key];
                $type       = $file['type'][$key];
                $error      = $file['error'][$key];
                
                $fileName = basename($image_name);
                $fileEx = pathinfo($fileName,PATHINFO_EXTENSION);
                $fileName = uniqid('', true) . "." . $fileEx; //change file name to random sting with its ext   

                $targetFilePath = $targetDir . $fileName;
                if ( move_uploaded_file($tmp_name, $targetFilePath) ) {
                    
                    $sql = 'INSERT INTO items (user_id, title, tag, description)
                    VALUES (:user_id, :title, :tag, :description)';

                    $stmt = $db->prepare($sql);

                    $stmt->bindValue(':user_id', $userID, PDO::PARAM_STR);
                    $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
                    $stmt->bindValue(':tag', $this->tag, PDO::PARAM_STR);
                    $stmt->bindValue(':description', $this->desc, PDO::PARAM_STR);
                    
                    continue;
                    
                } else {
                    Flash::addMessage("Произошла ошибка с загрузкой изображения #$i. Обьявление было принято. 
                    Попытайтесь добавить фото через свой профиль еще раз", Flash::WARNING);;
                }

                echo "$i. image_name=$image_name,     tmp_name=$tmp_name,     size=$size,      type=$type.<br>";
                $i++;
            }

        }    
            

        /*
        
        $fileName = basename($file["name"]);
        $fileEx = pathinfo($fileName,PATHINFO_EXTENSION);
        $fileName = uniqid('', true) . "." . $fileEx; //change file name to random sting with its ext     
        
        $targetFilePath = $targetDir . $fileName;
        if ( move_uploaded_file($file["tmp_name"], $targetFilePath) ) {
            
            //TODO
            return true;
            
        } else {
            $this->errors[] = 'Sorry, there was an error uploading your file..';
        }
        */          
        return false;
    }
    
    
    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    private function validateImg($file)
    { 
        //loop through each submited image and validate it
        $i = 1;
        foreach($file['name'] as $key=>$val){
            $tmp_name   = $file['tmp_name'][$key];
            $size       = $file['size'][$key];
            $error      = $file['error'][$key];
            
            switch ($error) {
                case 0:
                    break;
                case 4:
                    $this->errors[] = "Image#$i. No file sent.";
                case 1:
                case 2:
                    $this->errors[] = "Image#$i. Exceeded filesize limit.";
                default:
                    $this->errors[] = "Image#$i. Unknown errors.";
            }
                
            if ($size > 1000000) {
                $this->errors[] = "Image#$i. Exceeded filesize limit..";
            }
            
            // IMAGETYPE_JPEG as well verify .jpg files
            if ( exif_imagetype($tmp_name) != IMAGETYPE_JPEG && exif_imagetype($tmp_name) != IMAGETYPE_PNG ) {
            $tmp = exif_imagetype($tmp_name);
                $this->errors[] = "Image#$i. Sorry, only images files are allowed to upload. $tmp";
            }
            $i++;
        }
        
    }
    
    public function getAll()
    {
        $sql = 'SELECT * FROM items ORDER BY item_id DESC';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFour($page) {
        if ($page!=0) { $page += 4; }
        $sql = "SELECT * FROM items ORDER BY item_id DESC LIMIT $page,4";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByID($id) {
        $sql = 'SELECT * FROM items WHERE item_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        return $stmt->fetch();
    }
    /**
     * Update the user's item
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateItem($data)
    {
    
    }
}
