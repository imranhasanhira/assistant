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
        return $this->ci->session->userdata(SESSION_IS_LOGGED_IN) == true;
    }

    public function isAdminLoggedIn() {
        return $this->isLoggedIn() && ($this->ci->session->userdata(SESSION_LOGGED_IN_USERNAME) == 'admin');
    }

    public function storeLoginInfo($userInfo) {
        $this->ci->session->set_userdata(SESSION_IS_LOGGED_IN, TRUE);
        $this->ci->session->set_userdata(SESSION_LOGGED_IN_USERNAME, $userInfo['username']);
        $this->ci->session->set_userdata(SESSION_LOGGED_IN_USER_ID, $userInfo['id']);
    }

    public function clearLoginInfo() {
        $this->ci->session->unset_userdata(SESSION_IS_LOGGED_IN);
        $this->ci->session->set_userdata(SESSION_LOGGED_IN_USERNAME);
    }

}

?>
