<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 7/4/2019
 * Time: 12:34 PM
 */

class AppController {

    public function __construct($session) {
        //parent::__construct($admin, $session);
        //$this->admin = $admin;
        $this->session = $session;
        $this->database = $this->connect();
    }

    public function connect(){
        $servername = "localhost:3306";
        $username = "root";
        $password = "root";

        $db = new PDO("mysql:host=$servername;dbname=iRoom;charset=utf8mb4", $username, $password);
        return $db;
    }

    public function foo() {

    }
}