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

    public function index(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        //dirname(__DIR__,2) . '/dorne/views'
        $twig = new Twig\Environment($loader);
        //return $twig;
        echo $twig->render('index.twig');
    }

    public function login(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        //dirname(__DIR__,2) . '/dorne/views'
        $twig = new Twig\Environment($loader);
        //return $twig;
        echo $twig->render('login.twig');
    }
}