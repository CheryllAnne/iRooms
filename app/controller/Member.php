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
error_reporting(E_ALL ^ E_NOTICE);

require_once 'Admin.php';
require_once 'Session.php';

class Member {

    //protected $admin;

    /**
     * Member constructor.
     * @param $admin
     */
    public function __construct($admin, $session) {
        //parent::__construct($admin);
        $this->admin = $admin;
        $this->database = $this->admin->connect();
        $this->session = $session;
    }

    public function newMember(Request $request, Response $response){

        $error = "Error! Please try again";
        $success = "New Member added!";

        $firstName = json_decode($request->getBody())->firstName;
        $lastName = json_decode($request->getBody())->lastName;
        $email = json_decode($request->getBody())->email;
        $password = json_decode($request->getBody())->password;
        $newMember = "Insert into UserAcc (first_name, last_name, email, password) VALUES (?,?,?,?)";
        $result = $this->database->prepare($newMember)->execute([$firstName, $lastName, $email, $password]);
        //$result->execute([$adname, $email, $password]);

        if($result == true){
            return $response->withStatus(200)->withJson($success);
        }
        else{
            return $response->withStatus(404)->withJson($error);
        }
    }

    public function mLogin(Request $request, Response $response){

        $error = "An error has occured! Please try again";
        $success = "Logged In!";

        $email = json_decode($request->getBody())->email; //striptags to avoid cross site scripting
        //$password = json_decode($request->getBody())->password;
        //$password = password_hash($password1, PASSWORD_DEFAULT);
        $login = "SELECT * FROM `user` WHERE email = ? "; ///////////
        $result = $this->database->prepare($login) or die($this->database->error);
        $result->execute([$email]);

        if($result == true){
            $this->session->set('logged_id', true);
            return $response->withStatus(200)->withJson($success);
        }
        else {
            return $response->withStatus(404)->withJson($error);
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

    public function roomDelete(Request $request, Response $response, $args){

        $error = "Post doesn't exist";
        $success = "Successfully deleted";

        $id = $args['id'];
        $deleteRoom = "Delete from Room where id = $id";
        $result = $this->database->prepare($deleteRoom) or die($this->database->error);
        $result->bindValue('id', $id);
        $result->execute();

        if($result == true){
            return $response->withStatus(200)->withJson($success);
        }
        else{
            return $response->withStatus(400)->withJson($error);
        }

    }

    public function viewRooms(Request $request, Response $response){

        $viewRoom = "Select * from Room order by id desc";
        $result = $this->database->query($viewRoom);

        if ($result == true){
            while ($row = $result->fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
            }

            if (isset($data)){
                header( 'Content-Type: application/json' );
                echo json_encode( $data );
                return $response->withJson(200);
            }
        } else{
            return $response->withJson(400);
        }
    }

    public function addAdvertisement(Request $request, Response $response){


    }


}

