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
        $this->showView('home/login');
    }

}

?>
