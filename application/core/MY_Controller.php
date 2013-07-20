<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class MY_Controller extends CI_Controller {

    private $sidebarMenus;
    private $pageTitle;
    private $messages;

    public function __construct() {
        parent::__construct();

        $this->pageTitle = "Personal Assistance";
    }

    public function setSidebarMenus($sMenus = array()) {
        $this->sidebarMenus = array(
            'Home' => site_url(),
        );
        $this->sidebarMenus = array_merge($this->sidebarMenus, $sMenus);
    }

    public function addMessage($type, $message) {
        $this->messages[$type] = $message;
    }

    public function showView($view = NULL, $data = array()) {
        if(!$this->authenticator->isLoggedIn()){
            $this->load->helper('form');
        }
        
        $this->load->view('header', array(
            'pageTitle' => $this->pageTitle,
            'isLoggedIn' => $this->authenticator->isLoggedIn(),
            'sidebarMenus' => $this->sidebarMenus,
            'messages' => $this->messages
        ));

        if ($this->authenticator->isLoggedIn() && $view != NULL) {
            $this->load->view($view, $data);
        } else {
            $this->load->view('public', array(
                'ipAddress' => $this->input->ip_address()
            ));
        }
        $this->load->view('footer');
    }

}

?>
