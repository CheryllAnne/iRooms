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


        $success = "Logged In!";

        $adLogin = $request->getParsedBody();
        $email = $adLogin['email']; //striptags to avoid cross site scripting
        $password = $adLogin['password'];
        //$password = password_hash($password1, PASSWORD_DEFAULT);
        $login = $this->database->prepare("SELECT * FROM `admin` WHERE email = ? AND password = ?"); ///////////
        $result = $login->execute([$email, $password]);
        $row = $login->fetch( PDO::FETCH_ASSOC );


        if($row == true){

            if(password_verify($password, $adLogin['password']))
            $this->session->set('logged_id', true);
            $this->session->set('admin', $adLogin);
            echo $this->twig->render('admin.twig', array('adLogin' => $adLogin, 'rooms' => $this->session->get('rooms')));
//            return $response->withStatus(200)->withJson($success);
        }
        else {

            $error = "Login Unsuccessful! Please try again";
            echo $this->twig->render('adminLogin.twig', array('error' => $error));
        }
    }

    public function newAdmin(Request $request, Response $response){

        $error = "Error! Please try again";
        $success = "Admin added!";
        $admin = $request->getParsedBody();
        $adname = $admin['adname'];
        $email = $admin['email'];
        $password = $admin['password'];
//        var_dump($adname);
        $newAdmin = "Insert into admin (name, email, password) VALUES (?,?,?)";
        $result = $this->database->prepare($newAdmin)->execute([$adname, $email, $password]);
        //$result->execute([$adname, $email, $password]);

        if($result == true){
            return $response->withRedirect('/admin');
        }
        else{
            return $response->withRedirect('/');
        }
    }

    public function viewAdmins(Request $request, Response $response){

//        $error = "Error";
        $adminView = "Select * from `admin` order by id";
        $result = $this->database->query($adminView);

        if($result == true){

            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $admin[]= $row;

            }

            //$this->session->set('logged_id', true);
            $this->session->set('admin', $admin);
            echo $this->twig->render('viewList.twig', array('admin' => $this->session->get('admin'), 'adname' => $this->session->get('adname') ,
                'email' => $this->session->get('email')));
//            return $response->withRedirect('/adminList');

        }else{
            return $response->withRedirect('/');
        }
    }

    public function viewList( Request $request, \Slim\Http\Response $response ) {

        $count = 0;
        $viewRoom = "Select * from `room` order by roomID";
        $result = $this->database->query( $viewRoom );
        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $rooms[] = $row;
                $count = $count + 1;
            }

            $this->session->set('rooms', $rooms);
//            $rooms2 = $this->session->get('rooms');
//            var_dump($rooms);
            echo $this->twig->render('adminManage.twig', array('i' => $count, 'rooms' => $this->session->get('rooms'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));

//            }

        } else {
            return $response->withRedirect('/');
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