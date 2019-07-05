<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 7/4/2019
 * Time: 12:06 PM
 */

class Home{

    public function __get( $name ) {
        // TODO: Implement __get() method.
        return $this->value[$name];
    }

    public function connect(){
        $servername = "localhost:3306";
        $username = "root";
        $password = "root";

        $db = new PDO("mysql:host=$servername;dbname=iRoom;charset=utf8mb4", $username, $password);
        return $db;
    }

    public function index(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        //dirname(__DIR__,2) . '/dorne/views'
        $twig = new Twig\Environment($loader);

        echo $twig->render('index.twig');
    }
}