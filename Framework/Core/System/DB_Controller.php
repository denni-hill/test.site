<?php
class DB_Controller extends Base_Controller
{
    public static function setup()
    {
        $db = [];
        if(!R::testConnection()) {
            $db['name'] = "testdb";                  //DEFINE DATABASE
            $db['user'] = "root";                    //DEFINE DATABASE USER
            $db['password'] = "";                    //DEFINE DATABASE USER PASSWORD
            R::setup('mysql:host=localhost;dbname=' . $db['name'], $db['user'], $db['password']);
        }
    }
}