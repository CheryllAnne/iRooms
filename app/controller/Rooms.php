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

require_once 'Admin.php';
require_once 'Session.php';

class Rooms {

//    public function __construct($admin, $session, $member, $member_profile) {
//
//        $this->admin = $admin;
//        $this->session = $session;
//        $this->member = $member;
//        $this->member_profile = $member_profile;
//        $this->database = $this->admin->connect();
//    }

    public function __construct($admin, $session, $member, $member_profile) {

        parent::__construct();

        $this->member = 11;
        $this->member_profile = 22;
    }

    public function viewRooms( Request $request, Response $response ) {

        $viewRoom = "Select * from Room order by id desc";
        $result = $this->database->query( $viewRoom );

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $data[] = $row;
            }

            if ( isset( $data ) ) {
                header( 'Content-Type: application/json' );
                echo json_encode( $data );
                return $response->withJson( 200 );
            }
        } else {
            return $response->withJson( 400 );
        }
    }

    public function roomAdd(Request $request, Response $response){

        $error = "An error has occured! Please try again";
        $success = "New Room Added!";

        $roomName = json_decode($request->getBody())->roomName;
        $roomType = json_decode($request->getBody())->roomType;
        $description = json_decode($request->getBody())->description;
        $occupancy = json_decode($request->getBody())->occupancy;
        $price = json_decode($request->getBody())->price;
        $image = null;
        $file_type = null;
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $date = date("F d,Y");
        $addRoom = "Insert into Room (roomName, roomType, description, occupancy, price, image, fileType, date) VALUES (????????)";
        $result = $this->database->prepare($addRoom)or die($this->database->error);
        $result->bindValue('id', $id);
        $result->execute([$roomName, $roomType, $description, $occupancy, $price, $image, $file_type, $date]);

        if($result == true){
            return $response->withStatus(200)->withJson($success);
        }
        else {
            return $response->withStatus(400)->withJson($error);
        }
    }

    public function roomEdit(Request $request, Response $response, $args){

        $error = "Editing Failed!!";
        $success = "Successfully Edited";

        $id = $args['id'];
        $description = json_decode($request->getBody())->description;
        $price = json_decode($request->getBody())->price;
        $editRoom = "Update Room Set description = ?, price = ? where id = ?";
        $result = $this->database->prepare($editRoom) or die($this->database->error);
        $result->execute([$description, $price, $id]);

        if($result == true){
            return $response->withStatus(200)->withJson($success);
        }
        else{
            return $response->withStatus(400)->withJson($error);
        }
    }

    public function roomDelete( Request $request, Response $response, $args ) {

        $error = "Post doesn't exist";
        $success = "Successfully deleted";

        $id = $args['id'];
        $deleteRoom = "Delete from Room where id = $id";
        $result = $this->database->prepare( $deleteRoom ) or die( $this->database->error );
        $result->bindValue( 'id', $id );
        $result->execute();

        if ( $result == true ) {
            return $response->withStatus( 200 )->withJson( $success );
        } else {
            return $response->withStatus( 400 )->withJson( $error );
        }

    }

}