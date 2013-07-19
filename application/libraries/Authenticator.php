<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Authenticator {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
    }

    public function isLoggedIn() {
        return $this->ci->session->userdata('logged_in_user') != NULL;
    }

    public function isAdminLoggedIn() {
        return $this->ci->session->userdata('logged_in_user') != 'admin';
    }
    
    public function doLogin($username, $password){
        
    }

}

?>
