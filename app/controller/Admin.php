<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 6/25/2019
 * Time: 4:18 PM
 */
//namespace controller;
//use PDO;
use utility\Session;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'Session.php';

//class AppController{
//
//    public function __construct(ContainerInterface $container) {
//
//    }
//
//    public function __get( $name ) {
//        // TODO: Implement __get() method.
//        return $this->value[$name];
//    }
//}


class Admin{

    protected $database;
    protected $value = array();

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

    public function __construct($session) {
        //parent::__construct();
        $this->database = $this->connect();
        $this->session = $session;

    }

    public function newAdmin(Request $request, Response $response){

        $error = "Error! Please try again";
        $success = "Admin added!";

        $adname = json_decode($request->getBody())->adname;
        $email = json_decode($request->getBody())->email;
        $password = json_decode($request->getBody())->password;
        var_dump($adname);
        $newAdmin = "Insert into admin (name, email, password) VALUES (?,?,?)";
        $result = $this->database->prepare($newAdmin)->execute([$adname, $email, $password]);
        //$result->execute([$adname, $email, $password]);

        if($result == true){
            return $response->withStatus(200)->withJson($success);
        }
        else{
            return $response->withStatus(404)->withJson($error);
        }
    }

    public function viewAdmins(Request $request, Response $response){

        $adminView = "Select * from admin order by id";
        $result = $this->database->query($adminView);

        if($result == true){
            while($row = $result->fetch( PDO::FETCH_ASSOC)){
                $data[] = $row;
            }

            if(isset($data)){
                header('Content-Type: application/json');
                echo json_encode($data);
                //return $response->withJson(200);
            }
        }else{
            return $response->withJson(400);
        }
    }

    public function viewMembers(Request $request, Response $response){

        $memberView = "Select * from UserAcc order by id";
        $result = $this->database->query($memberView);

        if($result == true){
            while($row = $result->fetch( PDO::FETCH_ASSOC)){
                $data[] = $row;
            }

            if(isset($data)){
                header('Content-Type: application/json');
                echo json_encode($data);
                //return $response->withJson(200);
            }
        }else{
            return $response->withJson(400);
        }
    }

    public function deleteMP($request, $response, $args) {
        $id = $args['id'];
        $error = "Member profile does not exist!";
        $success = "Successfully deleted!";
        $deleteMP = "Delete from UserProfile WHERE id=$id";
        $result = $this->database->prepare($deleteMP) or die($this->database->error);
        $result->bindValue('id', $id);
        $result->execute();

        if ($result == true) {
            return $response->withStatus(200)->withJson($success);
        } else {
            return $response->withStatus(404)->withJson($error);
        }
    }



    public function addState(){

    }



}