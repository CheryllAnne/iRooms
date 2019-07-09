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

require_once 'AppController.php';
require_once 'Admin.php';
require_once 'Session.php';

class Member extends AppController {

    public function __construct( $session ) {
        parent::__construct( $session );
    }

    public function mLogin(Request $request, Response $response){

        $error = "An error has occured! Please try again";
        $success = "Logged In!";

        $email = json_decode($request->getBody())->email; //striptags to avoid cross site scripting
        //$password = json_decode($request->getBody())->password;
        //$password = password_hash($password1, PASSWORD_DEFAULT);
        $login = "SELECT * FROM `UserAcc` WHERE email = ? "; ///////////
        $result = $this->database->prepare($login) or die($this->database->error);
        $result->execute([$email]);

        if($result == true){
            //$this->session->set('logged_id', true);
            return $response->withRedirect('/');
        }
        else {
            return $response->withRedirect('/login');
        }

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


    public function addAdvertisement(Request $request, Response $response){

    }


}

//echo $twig->render('login.twig');
