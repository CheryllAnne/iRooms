<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 6/24/2019
 * Time: 4:02 PM
 */
error_reporting(E_ALL ^ E_NOTICE);
require "../vendor/autoload.php";
include_once "../app/controller/AppController.php";
include_once '../app/controller/Session.php';
include_once '../app/controller/MemberProfile.php';
include_once '../app/controller/Member.php';
include_once '../app/controller/Rooms.php';
include_once '../app/routes.php';

$container = $app->getContainer();

$container['Session'] = function ($container){
    $session = new \utility\Session();
    return $session;
};

$container['Admin'] = function ($container){
    $session = $container->get('Session');
    $admin = new Admin($session);
    return $admin;
};

$container['Member'] = function ($container){
    //$admin = $container->get('Admin');
    $session = $container->get('Session');
    $member = new Member($session);
    return $member;
};

$container['Home'] = function ($container){
    //$admin = $container->get('Admin');
    $session = $container->get('Session');
    $home = new Home($session);
    return $home;
};

$container['AppController'] = function ($container){
    //$admin = $container->get('Admin');
    $session = $container->get('Session');
    $app_controller = new AppController($session);
    return $app_controller;
};

$container['MemberProfile'] = function ($container){
//    $admin = $container->get('Admin');
    $session = $container->get('Session');
    $member = $container->get('Member');
    $member_profile = new MemberProfile($session, $member);
    return $member_profile;
};

$container['Rooms'] = function ($container){
    //$admin = $container->get('Admin');
    $session = $container->get('Session');
    $member = $container->get('Member');
    $member_profile = $container->get('MemberProfile');
    $rooms = new Rooms($session, $member, $member_profile);
    return $rooms;
};

$app->run();


//Twig_Autoload::register();
//$loader = new Twig\Loader\FilesystemLoader(__DIR__);
//$twig = new Twig\Environment($loader);
//
//echo $twig->render('views/index.twig');

