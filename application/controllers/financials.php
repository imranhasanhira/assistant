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
        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);

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

        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);
        $this->load->model('financial_accounts');
        $account = $this->financial_accounts->getAccount($userID, $accountID);
        if ($account == NULL) {
            $this->addMessage('error', 'Account not found.');
            $this->showView('financials');
            return;
        }

        $categoryID = filter_var($this->input->get('category', TRUE), FILTER_VALIDATE_INT);
        if ($categoryID == FALSE || $categoryID < 0) {
            $categoryID = -1;
        }

        $limit = filter_var($this->input->get('limit', TRUE), FILTER_VALIDATE_INT);
        if ($limit == FALSE || $limit <= 0 || $limit > TRANSACTION_PAGINATION_MAX_VALUE) {
            $limit = TRANSACTION_PAGINATION_VALUE;
        }

        $page = filter_var($this->input->get('page', TRUE), FILTER_VALIDATE_INT);
        if ($page == FALSE || $page < 0) {
            $page = 0;
        } else {
            $page -=1;
        }
        $offset = $page * $limit;

        $this->load->model('financial_transactions');
        $totalTransactionsCount = $this->financial_transactions->getTotalTransactionCount($accountID);
        $transactions = $this->financial_transactions->getTransactions($accountID, $categoryID, $offset, $limit);

        $this->load->model('financial_categories');
        $categories = $this->financial_categories->getCategories($userID);

        $this->showView('financials/account_details', array(
            'totalTransactionsCount' => $totalTransactionsCount,
            'account' => $account,
            'transactions' => $transactions,
            'totalTransactionCount' => $totalTransactionsCount,
            'currentPage' => $page,
            'pageSize' => $limit,
            'currentCategoryID' => $categoryID,
            'categories' => $categories,
            'accountID' => $accountID
        ));
    }

    public function transaction() {
        
    }

}

?>
