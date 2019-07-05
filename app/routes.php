<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 6/25/2019
 * Time: 4:50 PM
 */

use utility\Session;
//use controller\Admin;

require_once 'controller/Session.php';
require_once 'controller/Home.php';
require_once 'controller/Admin.php';
require_once 'controller/Member.php';
require_once '../vendor/autoload.php';

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

//$container = $app->getContainer();
//
//$container['Session'] = function ($container){
//    $session = new Session();
//    return $session;
//};
//
//$container['Admin'] = function ($container){
//    $session = $container->get('Session');
//    $admin = new Admin($session);
//    return $admin;
//};
//
//$container['Member'] = function ($container){
//    $admin = $container->get('Admin');
//    $session = $container->get('Session');
//    $member = new Member($admin, $session);
//    return $member;
//};
//
//$container['MemberProfile'] = function ($container){
//    $admin = $container->get('Admin');
//    $member = $container->get('Member');
//    $session = $container->get('Session');
//    $member_profile = new MemberProfile($admin, $member, $session);
//    return $member_profile;
//};
//
//$container['Rooms'] = function ($container){
//    $admin = $container->get('Admin');
//    $session = $container->get('Session');
//    $member = $container->get('Member');
//    $member_profile = $container->get('MemberProfile');
//    $rooms = new Rooms($admin, $session, $member, $member_profile);
//    return $rooms;
//};

$app->group('/v1/admin', function () use ($app){

    $app->post('/new', Admin::class . ':newAdmin');

    $app->get('/view', Admin::class . ':viewAdmins');
});

$app->group('/v1/member', function () use ($app){

    $app->post('/new', Member::class . ':newMember');

    $app->get('/view', Member::class . ':viewMembers');
});

$app->get('/', Home::class . ':index');

$app->run();