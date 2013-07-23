<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financial_accounts extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getTotalBalance($accountID) {
        if (!isset($this->financial_transactions))
            $this->load->model('financial_transactions');
        return $this->financial_transactions->getTotalBalance($accountID);
    }

    public function getAccounts($userID) {
        $accounts['2'] = array('name' => 'Account name 2');
        $accounts['1'] = array('name' => 'Account name 1');
        return $accounts;
    }

    public function addAccount($userID, $accountName) {
        $account['name'] = $accountName;
        $account['id'] = 1;
        return $account;
    }

    public function getAccount($userID, $accountID) {
        $account['name'] = 'Account name 0';
        $account['id'] = $accountID;
        return $account;
    }

}

?>
