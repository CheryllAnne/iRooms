<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 6/26/2019
 * Time: 3:13 PM
 */

use utility\Session;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

error_reporting( E_ALL ^ E_NOTICE );

require_once 'AppController.php';
require_once 'Admin.php';
require_once 'Session.php';

//session_start();

class Member extends AppController {

    public function __construct( $session ) {
        parent::__construct( $session );
    }

    public function mLogin( Request $request, Response $response, $args ) {

        $member = $request->getParsedBody();
        $email = $member['email'];
        $password = $member['password'];
        $login = $this->database->prepare("SELECT * FROM `UserAcc` WHERE email = ? AND password = ? "); ///////////
        $result = $login->execute( [$email, $password] );
        $data = $login->fetch(PDO::FETCH_ASSOC);
        //var_dump($result);

        if($data == null){
            $this->session->flash('fail', 'Login Unsuccessful! Please try again');
            echo $this->twig->render('login.twig', array('session' => $_SESSION, 'fail'=>$this->session->get('fail')));
        }
        else{
            if(password_verify($password, $member['password']))
                $this->session->set( 'member_login', true );
                $this->session->set( 'first_name', $data['first_name'] );
                echo $this->twig->render( 'MemberProfile.twig', array( 'members' => $this->session->get('members'), 'first_name' => $this->session->get( 'first_name' ) ) );
        }
    }

    public function newMember( Request $request, Response $response ) {

        $register = $request->getParsedBody();
        $firstName = $register['first_name'];
        $lastName = $register['last_name'];
        $email = $register['email'];
        $password = $register['password'];
        $newMember = "Insert into UserAcc (first_name, last_name, email, password) VALUES (?,?,?,?)";
        $result = $this->database->prepare( $newMember )->execute( [ $firstName, $lastName, $email, $password ] );

        if ( $result == true ) {
            echo $this->twig->render('MemberProfile.twig', array('firstName' => $this->session->get('first_name')));
        } else {
            return $response->withRedirect( '/login' );
        }
    }

    public function viewMember( Request $request, Response $response ) {

        $count = 0;
        $memberView = "Select * from UserAcc order by id";
        $result = $this->database->query( $memberView );

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $members[] = $row;
                $count = $count + 1;
            }

            $this->session->set( 'members', $members );
            echo $this->twig->render('viewList.twig', array('i' => $count, 'members' => $this->session->get('members'), 'firstName' => $this->session->get('firstName'),
                'lastName' => $this->session->get('lastName'), 'email' => $this->session->get('email')));
        } else {
            return $response->withRedirect( '/' );
        }
    }

    public function viewListofRooms( Request $request, \Slim\Http\Response $response ) {

        $count = 0;
        $viewRoom = "Select * from `room` order by roomID";
        $result = $this->database->query( $viewRoom );

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $rooms[] = $row;
                $count = $count + 1;
            }

            $this->session->set('rooms', $rooms);
            echo $this->twig->render('memberRoomMng.twig', array('i' => $count, 'rooms' => $this->session->get('rooms'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));

        } else {
            return $response->withRedirect('/');
        }

    }

    public function roomMDelete( Request $request, Response $response, $args ) {

        if ($this->session->check('member_login') == false) {
            return $response->withRedirect('/');
            exit;
        }
        $error = "Post doesn't exist";

        $roomID = $args['roomID'];
//        $id = $args['id'];
        $deleteRoom = "Delete from `room` where roomID = ?";
        $result = $this->database->prepare( $deleteRoom ) or die( $this->database->error );
        //$result->bindValue( 'room_id', $roomID );
        $result->execute([$roomID]);

        if ( $result == true ) {
            $success = "Successfully deleted";
            return $response->withRedirect('/v1/member/rooms');
            //echo $this->twig->render('adminManage.twig',array('roomID'=>$roomID, 'success'=>$success));
        } else {
            return $response->withStatus( 400 )->withJson( $error );
        }

    }

    public function logout(\Slim\Http\Response $response){
        $this->session->unsetSession();
        return $response->withRedirect('/');

    }


    public function addAdvertisement( Request $request, Response $response ) {

    }


}


//echo $twig->render('login.twig');
