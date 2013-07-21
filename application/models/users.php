<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Users extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doLoginWithUsername($username, $passwordHash) {
        return array(
            'username' => $username,
            'id' => 2
        );
    }

    public function doLoginWithEmail($email, $passwordHash) {
        return array(
            'username' => $username,
            'email' => $email,
            'id' => 1
        );
    }

}

?>
