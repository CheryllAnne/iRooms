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

        $contact = $request->getParsedBody();
        $username = $contact['name'];
//        $address = json_decode($request->getBody())->address;
//        $state = json_decode($request->getBody())->state;
        $email = $contact['email'];
        $contactNo = $contact['contactNo'];
        $background = $contact['company_background'];
        $addDetails = "Insert into UserProfile (name, email, contactNo, company_background) VALUES (?,?,?,?)";
        $result = $this->database->prepare($addDetails);
        $result->execute([$username, $email, $contactNo, $background]);

        if($result == true){
            $this->session->flash('add', ' Contact Details Updated!');

        }
        else {
            $this->session->flash('error', 'An ERROR has occured');
        }


    }

    public function viewCD(Request $request, Response $response, $args){

        $count = 0;
        $contactID = $args['id'];
        $viewCD = "Select * from UserProfile where id = ?";
        $result = $this->database->prepare($viewCD);
        $result->execute([$contactID]);

            if ($result == true){
                while ($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $contact[] = $row;
                    $count = $count + 1;
                }

                $this->session->set('contact', $contact);

                $this->twig->render('MemberProfile.twig', array('i' => $count, 'id' => $contactID, 'contact' => $this->session->get('contact')));

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