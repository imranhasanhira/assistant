<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financial_transactions extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getTotalBalance($accountID) {
        if ($accountID == NULL) {
            return NULL;
        }

        return 10000;
    }

    public function getTransactions($accountID, $categories = NULL, $limit = 0, $offset = 0) {
        $transactions['0'] = array(
            'categoryID' => 0,
            'secondaryAccountID' => 2,
            'title' => 'transaction title',
            'description' => 'transaction description 0',
            'amount' => 100,
            'date' => date()
        );

        $transactions['1'] = array(
            'categoryID' => 0,
            'secondaryAccountID' => 2,
            'title' => 'transaction title 2',
            'description' => 'transaction description 1',
            'amount' => 50,
            'date' => date()
        );

        return $transactions;
    }
    
    public function getBorrowedAmount(){
        
    }

}

?>
