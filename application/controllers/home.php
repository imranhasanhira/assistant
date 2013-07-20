<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $mode = $this->input->post('mode');
        if ($mode != NULL) {
            if ($mode == 'login') {
                $this->login();
            } elseif ($mode == 'logout') {
                $this->logout();
            }
        } else {
            $this->showView('home');
        }
    }

    private function login() {

        $this->load->library('form_validation');
        $config = array(
            array(
                'field' => 'username-or-email',
                'label' => 'Username or email',
                'rules' => 'trim|required|min_length[4]|max_length[30]|xss_clean'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[8]|max_length[30]|xss_clean'
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $this->addMessage('error', $this->form_validation->error_string());
            $this->showView();
        } else {
            $password = $this->input->post('password');
            $passwordHash = sha1($password);
            $usernameOrEmail = $this->input->post('username-or-email');

            $this->load->model('users');
            $user = NULL;
            $error = "";
            if ($this->form_validation->valid_email($usernameOrEmail)) {
                $email = $usernameOrEmail;
                $user = $this->users->doLoginWithUsername($email, $passwordHash);
                if ($user == NULL) {
                    $error = "Email or password not accepted";
                }
            } else {
                $username = $usernameOrEmail;
                $user = $this->users->doLoginWithUsername($username, $passwordHash);
                if ($user == NULL) {
                    $error = "Username or password not accepted";
                }
            }

            if ($user == NULL) {
                $this->addMessage('error', $error);
                $this->showView();
            } else {
                $this->authenticator->storeLoginInfo($user);
                $this->addMessage('success', "Successfully logged in.");
                $this->showView('home');
            }
        }
    }

    private function logout() {
        $this->authenticator->clearLoginInfo();
        $this->showView();
    }

}

?>
