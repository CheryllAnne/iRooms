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

$app->group('/v1/admin', function () use ($app){

//    $app->group('/', Admin::class . ':index');
    $app->post('/login', Admin::class . ':adLogin');
    // complete this function!!

    $app->post('/new', Admin::class . ':newAdmin');

    $app->get('/view', Admin::class . ':viewAdmins');

    $app->get('/viewList', Admin::class . ':viewList');


});

$app->group('/v1/member', function () use ($app){

    $app->post('/login', Member::class . ':mLogin');

    $app->post('/new', Member::class . ':newMember');

    $app->get('/view', Member::class . ':viewMember');



    $app->get('/rooms', Member::class . ':viewListofRooms');

    $app->post('/delete/{roomID}', Member::class . ':roomMDelete');

});

$app->group('/v1/room', function () use ($app){

    $app->get('/view', Rooms::class . ':viewRooms');

    $app->get('/viewARoom/{roomID}', Rooms::class . ':viewARoom');

    $app->post('/add', Rooms::class . ':roomAdd');

    $app->put('/edit/{id}', Rooms::class . ':roomEdit');

    $app->post('/delete/{roomID}', Rooms::class . ':roomDelete');

    $app->get('/search', Rooms::class . ':findRoom');
});

$app->get('/', Home::class . ':index');

$app->get('/login', Home::class . ':login');

$app->get('/adminLogin', Home::class . ':adminLogin');

$app->get('/listings', Home::class . ':rooms');

$app->get('/admin', Home::class . ':admin');

$app->get('/adminList', Home::class . ':adminList');

$app->get('/members', Home::class . ':members');

$app->get('/details', Home::class . ':memDetails');

$app->get('/addListing', Home::class . ':addList');

$app->get('/memberProfile', Home::class . ':memProfile');

//$app->run();