<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 7/4/2019
 * Time: 12:06 PM
 */

class Home extends AppController {

    public function __construct( $session ) {
        parent::__construct( $session );
    }

    public function __get( $name ) {
        // TODO: Implement __get() method.
        return $this->value[$name];
    }

    public function index(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('index.twig');
    }

    public function adminLogin(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('adminLogin.twig');
    }

    public function login(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('login.twig');
    }

    public function rooms(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
//        $rooms2 = $this->session->get('rooms');
//        var_dump($rooms2);
//        echo $rooms2;
        echo $twig->render('listing.twig', array('rooms' => $this->session->get('rooms')));
    }

    public function admin(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
//        $admin2 = $this->session->get('admin');
//        var_dump($admin2);
//        echo ($admin2);
        echo $twig->render('admin.twig', array('admin' => $this->session->get('admin'), 'adname' => $this->session->get('adname') ,
            'email' => $this->session->get('email')));
    }

    public function adminList(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        $admin2 = $this->session->get('admin');
        var_dump($admin2);
        echo ($admin2);
        echo $twig->render('viewList.twig', array('admin' => $this->session->get('admin'), 'adname' => $this->session->get('adname') ,
            'email' => $this->session->get('email')));
    }

    public function members(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
//        $members2 = $this->session->get('members');
//        var_dump($members2);
//        echo $members2;
        echo $twig->render('viewList.twig', array('members' => $this->session->get('members'), 'firstName' => $this->session->get('firstName'),
            'lastName' => $this->session->get('lastName'), 'email' => $this->session->get('email')));
    }

    public function addList(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('addListing.twig');
    }

    public function memDetails(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('profile.twig');
    }

    public function memProfile(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('MemberProfile.twig', array('members' => $this->session->get('members')));
    }

}