<?php
/**
 * Created by PhpStorm.
 * User: CHERYLLANNE
 * Date: 6/27/2019
 * Time: 9:27 AM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'AppController.php';
require_once 'Admin.php';
require_once 'Session.php';

class MemberProfile extends AppController {

    public function __construct($session, $member) {
        parent::__construct( $session );
        $this->member = $member;
    }

    public function contactDetails(Request $request, Response $response){

        $error = "Error! Please try again";
        $success = "New Member added!";

        $username = json_decode($request->getBody())->username;
        $address = json_decode($request->getBody())->address;
        $state = json_decode($request->getBody())->state;
        $email = json_decode($request->getBody())->email;
        $contactNo = json_decode($request->getBody())->contactNo;
        $background = json_decode($request->getBody())->background;
        $addDetails = "Insert into UserProfile (name, address, state, email, contactNo, company_background) VALUES (??????)";
        $result = $this->database->prepare($addDetails) or die($this->database->error);
        $result->execute([$username, $address, $state, $email, $contactNo, $background]);

        if($result == true){
            return $response->withStatus(200)->withJson($success);
        }
        else {
            return $response->withStatus(400)->withJson($error);
        }

    }

    public function viewCD(Request $request, Response $response){

        $viewCD = "Select * from UserProfile order by id desc ";
        $result = $this->database->query($viewCD);

            if ($result == true){
                while ($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $data[] = $row;
                }

                if (isset($data)){
                    header('Content-Type: application/json');
                    echo json_encode($data);
                    return $response->withJson(200);
                }
            } else{
                return $response->withJson(400);
            }
    }

    public function editCD(Request $request, Response $response, $args){
        
        $id = $args['id'];
        $error = "Error! Please try again";
        $success = "Details edited";
        $address = json_decode($request->getBody())->address;
        $state = json_decode($request->getBody())->state;
        $contactNo = json_decode($request->getBody())->contactNo;
        $editCD = "Update UserProfile SET address = ?, state = ?, contactNo = ? WHERE id = ? ";
        $result = $this->database->prepare($editCD) or die($this->database->error);
        $result->bindValue('id', $id);
        $result->execute([$address, $state, $contactNo]);

        if ($result == true) {
            return $response->withStatus(200)->withJson($success);
        } else {
            return $response->withStatus(404)->withJson($error);
        }

    }
}