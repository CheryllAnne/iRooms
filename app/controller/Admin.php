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

require_once 'AppController.php';
require_once 'Session.php';

$loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
$twig = new Twig\Environment($loader);


class Admin extends AppController {

    protected $database;
    protected $value = array();

    public function __get( $name ) {
        // TODO: Implement __get() method.
        return $this->value[$name];
    }

    public function __construct( $session ) {
        parent::__construct( $session );
    }

    public function adLogin(Request $request, Response $response){

        $error = "An error has occured! Please try again";
        $success = "Logged In!";

        $email = json_decode($request->getBody())->email; //striptags to avoid cross site scripting
        //$password = json_decode($request->getBody())->password;
        //$password = password_hash($password1, PASSWORD_DEFAULT);
        $login = "SELECT * FROM `admin` WHERE email = ? "; ///////////
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
                return $response->withJson(200);
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


//echo $twig->render('login.twig');

?>