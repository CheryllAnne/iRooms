<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 7/2/2019
 * Time: 5:43 PM
 */
use utility\Session;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'AppController.php';
require_once 'Admin.php';
require_once 'Session.php';

//session_start();

class Rooms extends AppController {

//    public function __construct($admin, $session, $member, $member_profile) {
//
//        $this->admin = $admin;
//        $this->session = $session;
//        $this->member = $member;
//        $this->member_profile = $member_profile;
//        $this->database = $this->admin->connect();
//    }

    public function __construct($session, $member, $member_profile) {

        parent::__construct($session);
        //$this->admin = $admin;
        $this->member = $member;
        $this->member_profile = $member_profile;
    }


    public function roomAdd(Request $request, Response $response, $args){
//        if ($this->session->check('member_login') == false) {
//            $errormsg = "Please Login to add a new listing.";
//            echo $this->twig->render('addListing.twig', array('errormsg'=>$errormsg));
//            exit;
//        }
        $room = $request->getParsedBody();
        $roomName = $room['roomName'];
        $roomType = $room['roomType'];
        $description = $room['description'];
        $occupancy = $room['occupancy'];
        $price = $room['price'];
//        date_default_timezone_set('Asia/Kuala_Lumpur');
//        $date = date("F d,Y");
        $address = $room['address'];
        $state = $room['state'];
        $addRoom = "Insert into room (roomName, roomType, description, occupancy, price, address, state) VALUES (?,?,?,?,?,?,?)";
        $result = $this->database->prepare($addRoom);
        $result->execute([$roomName, $roomType, $description, $occupancy, $price, $address, $state]);

        if($result == true){
            $this->session->flash('add', ' Room Successfully added!');
        }
        else {
            $this->session->flash('error', 'ERROR!');
        }
        echo $this->twig->render('addListing.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error'), 'type' => $file_type, 'image' => $image, 'imageType' => $imageFileType, 'image_ext' => $image_ext));
    }

    public function upload_media() {
        $image_ext = array( "jpg", "jpeg", "gif", "png" );
//        $video_ext = array("mp4", "wma");
//        $audio_ext = "mp3";
        $target = "uploads/" . basename( $_FILES["file"]["name"] );
        $imageFileType = strtolower( pathinfo( $target, PATHINFO_EXTENSION ) );

        if ( move_uploaded_file( $_FILES["file"]["tmp_name"], $target ) ) {
            if ( in_array( $imageFileType, $image_ext ) ) {
                $file_type = "image";
                $this->roomAdd( $file_type );
                $this->session->flash( 'success', 'POST SUCCESSFUL!' );
                $this->session->flash( 'uploaded', 'The file ' . basename( $_FILES["file"]["name"] ) . ' has been successfully uploaded.' );

//            } elseif (in_array($imageFileType, $video_ext)) {
//                $file_type = "video";
//                $this->upload($file_type);
//                $this->session->flash('success', 'POST SUCCESSFUL!');
//                $this->session->flash('uploaded', 'The file ' . basename($_FILES["file"]["name"]) . ' has been successfully uploaded.');
//
//            } elseif (in_array($imageFileType, $audio_ext)) {
//                $file_type = "audio";
//                $this->upload($file_type);
//                $this->session->flash('success', 'POST SUCCESSFUL!');
//                $this->session->flash('uploaded', 'The file ' . basename($_FILES["file"]["name"]) . ' has been successfully uploaded.');
//
//            }

            } else {

                $file_type = NULL;
                $this->roomAdd( $file_type );
                $this->session->flash( 'success', 'POST SUCCESSFUL!' );

            }
        }
    }

    public function viewRooms( Request $request, \Slim\Http\Response $response ) {

        $count = 0;
        $viewRoom = "Select * from `room` order by roomID";
        $result = $this->database->query( $viewRoom );

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $rooms[] = $row;
                $count = $count + 1;
            }
            $this->session->set('rooms', $rooms);
            echo $this->twig->render('listing.twig', array('i' => $count, 'rooms' => $this->session->get('rooms'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            return $response->withRedirect('/');
        }
    }

    public function viewARoom( Request $request, \Slim\Http\Response $response, $args ) {

        $roomID = $args['roomID'];
        $viewRoom = "Select * from `room` where roomID = ?";
        $result = $this->database->prepare( $viewRoom );
        $result->execute([$roomID]);

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $rooms[] = $row;
            }
            $this->session->set('rooms', $rooms);
            echo $this->twig->render('single-listing.twig', array('roomID'=>$roomID, 'rooms' => $this->session->get('rooms'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            return $response->withRedirect('/');
        }
    }

    public function findRoom( Request $request, \Slim\Http\Response $response) {

        $count = 0;
        $room = $request->getQueryParams();
        $state = $room['state'];
        $findRoom = "Select * from `room` where `state` LIKE :state";
        $result = $this->database->prepare( $findRoom );
        $result->bindValue(':state', '%'.$state.'%');
        $result->execute();

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $rooms[] = $row;
                $count = $count + 1;
            }
            $this->session->set('rooms', $rooms);;
            echo $this->twig->render('listing.twig', array('i' => $count, 'search'=>$this->session->get('search'),  'rooms' => $this->session->get('rooms'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            return $response->withRedirect('/');
        }
//
    }

    public function roomEdit(Request $request, Response $response, $args){

        $error = "Editing Failed!!";
        $success = "Successfully Edited";
        $roomID = $args['roomID'];
        $room = $request->getParsedBody();
        $description = $room['description'];
        $price = $room['price'];
        $editRoom = "Update `room` Set description = ?, price = ? where roomID = ?";
        $result = $this->database->prepare($editRoom) or die($this->database->error);
        $result->execute([$description, $price, $roomID]);

        if($result == true){
            return $response->withStatus(200)->withJson($success);
        }
        else{
            return $response->withStatus(400)->withJson($error);
        }
    }

    public function roomDelete( Request $request, Response $response, $args ) {

        $error = "Post doesn't exist";
        $roomID = $args['roomID'];
        $deleteRoom = "Delete from `room` where roomID = ?";
        $result = $this->database->prepare( $deleteRoom ) or die( $this->database->error );
        //$result->bindValue( 'room_id', $roomID );
        $result->execute([$roomID]);

        if ( $result == true ) {
            $success = "Successfully deleted";
            return $response->withRedirect('/v1/admin/viewList');
            //echo $this->twig->render('adminManage.twig',array('roomID'=>$roomID, 'success'=>$success));
        } else {
            return $response->withStatus( 400 )->withJson( $error );
        }
    }

    public function searchRoom(Request $request, Response $response, $args){

        $search = $request->getParsedBody();
        $state = $search['state'];
        $stmt = $this->database->prepare("SELECT * FROM room WHERE state = ?");
        $stmt->execute([$state]);
        $arr = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$arr) exit('No rows');
        var_export($arr);
        $stmt = null;
    }
}