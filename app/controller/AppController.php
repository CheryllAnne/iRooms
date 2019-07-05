<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 7/4/2019
 * Time: 12:34 PM
 */

class AppController {
    public function __construct($admin, $session) {
        //parent::__construct($admin);
        $this->admin = $admin;
        $this->database = $this->admin->connect();
        $this->session = $session;
    }
    public function foo() {

    }
}