<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financials extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->authenticator->isLoggedIn()) {
            redirect('home');
        }
    }

    public function index() {
        $userID = $this->session->userdata(SESSION_LOGGED_IN_USERID);

        $mode = $this->input->post('mode');
        if ($mode == 'newaccount') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Account name', 'trim|required|min_length[3]|max_length[50]|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $error = $this->form_validation->error_string();
                $this->addMessage('error', $error);
            } else {
                $accountName = $this->input->post('name');
                $this->load->model('financial_accounts');
                $newAccountID = $this->financial_accounts->addAccount($userID, $accountName);
                if ($newAccountID == NULL) {
                    $this->addMessage('error', "Can't create account with name '" . $accountName . "'");
                } else {
                    $this->addMessage('success', "Account '" . $accountName . "' added succesfully.");
                }
            }
        }

//            $borrowedMoney = 
        $this->load->model('financial_accounts');
        $accounts = $this->financial_accounts->getAccounts($userID);

        $this->showView('financials/dashboard', array(
            'accounts' => $accounts
        ));
    }

    public function account($accountID = NULL) {
        if ($accountID == NULL) {
            $this->index();
            return;
        }

        $userID = $this->session->userdata(SESSION_LOGGED_IN_USERID);
        $this->load->model('financial_accounts');
        $account = $this->financial_accounts->getAccount($userID, $accountID);
        if ($account == NULL) {
            $this->addMessage('error', 'Account not found.');
            $this->showView('financials');
            return;
        }

        $categoryID = NULL;
        $offset = 0;
        $limit = TRANSACTION_PAGINATION_LIMIT;
        $mode = $this->input->post('mode');
        if ($mode == 'list-transactions') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('category-id', 'Category', 'numeric|xss_clean');
            $this->form_validation->set_rules('page', 'Page', 'numeric');
            $this->form_validation->set_rules('limit', 'Max count', 'numeric|less_than[1000]');
            if ($this->form_validation->run() == FALSE) {
                $this->addMessage('error', $this->form_validation->error_string());
            } else {
                $categoryID = $this->input->post('category-id');
                $limit = $this->input->post('limit');
                $offset = $this->input->post('page') * $limit;
            }
        }


        $this->load->model('financial_transactions');
        $totalTransactionsCount = $this->finanacial_transactions->getTotalTransactionsCount($accountID);
        $transactions = $this->financial_transaciton->getTransactions($accountID, $categoryID, $offset, $limit);

        $this->showView('financials/account', array(
            'totalTransactionsCount' => $totalTransactionsCount,
            'transactions' => $transactions
        ));
    }

}

?>
