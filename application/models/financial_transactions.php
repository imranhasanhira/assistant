<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financial_transactions extends CI_Model {

    private $totalTransactionCount = 50;

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
        $transactions = array();
        for ($i = 0; $i < $this->totalTransactionCount; $i++) {
            $transactions[$i] = array(
                'category' => 'cat-' . rand(1, 10),
                'secondaryAccountID' => rand(1, 7),
                'title' => 'transaction title ' . $i,
                'description' => 'transaction description ' . $i,
                'amount' => rand(1, 1000),
                'date' => date('d M, Y')
            );
        }
        return $transactions;
    }

    public function getBorrowedAmount() {
        return 100;
    }

    public function getTotalTransactionCount() {
        return $this->totalTransactionCount;
    }

}

?>
