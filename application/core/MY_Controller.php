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

    public function showView($view, $data = array()) {
        $this->load->view('header', array(
            'pageTitle' => $this->pageTitle,
            'isLoggedIn' => $this->authenticator->isLoggedIn(),
            'sidebarMenus' => $this->sidebarMenus
        ));

        $this->load->view($view, $data);
        $this->load->view('footer');
    }

}

?>
