<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financials extends MY_Controller {

    private $newTransactionModes = array('deposite', 'spend', 'transfer');
    private $genericNewTransactionModes = array('borrow', 'pay-back', 'lend', 'lend-back');
    private $existingTransactionModes = array('view', 'edit', 'trash');

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

    /**
     * 
     * @param <int> $accountID
     * @return 
     */
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

        $sortOptions = array(
            SORT_BY_DATE_DEC => 'Recent transactions',
            SORT_BY_DATE_INC => 'Older transactions',
            SORT_BY_AMOUNT_DEC => 'Large amounts first',
            SORT_BY_AMOUNT_INC => 'Small amounts first'
        );
        $currentSortOptionID = filter_var($this->input->get('sort-by', TRUE), FILTER_SANITIZE_STRING);
        $sortOptionInvalid = TRUE;
        foreach ($sortOptions as $sortOptionID => $sortOptionName) {
            if ($currentSortOptionID == $sortOptionID) {
                $sortOptionInvalid = FALSE;
            }
        }
        if ($currentSortOptionID == FALSE || !is_string($currentSortOptionID)) {
            $currentSortOptionID = 'date-dec';
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
            'accountID' => $accountID,
            'sortOptions' => $sortOptions,
            'currentSortOptionID' => $currentSortOptionID
        ));
    }

    /**
     * Calling flow 
     * 
     * transaction/deposite?account-id=<int>
     * transaction/spend?account-id=<int>
     * transaction/transfer?account-id=<int>
     * transaction/borrow
     * transaction/pay-back
     * transaction/lend
     * transaction/lend-back
     * transaction/view?(transaction-id <int>)
     * transaction/edit?(transaction-id <int>)
     * transaction/trash?(transaction-id <int>)
     * 
     * @param <mixed> $firstParam this can be 'depostite' , 'spend' , 'transfer' , 'edit' 
     * or any number which will be interpreted as transaction number.
     * 
     * @param <int> $secondParam
     */
    public function transaction($firstParam = NULL) {

        $mode = filter_var($this->input->post('mode', TRUE), FILTER_SANITIZE_STRING);
        if ($mode == 'formsubmit') {
            $this->handleFormsubmit($firstParam);
            return;
        }

        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);



        if (in_array($firstParam, $this->newTransactionModes, TRUE)) {
            $type = $firstParam;
            $accountID = filter_var($this->input->get('account-id', TRUE), FILTER_VALIDATE_INT);
            $account = NULL;
            if ($accountID != FALSE) {
                $this->load->model('financial_accounts');
                $account = $this->financial_accounts->getAccount($userID, $accountID);
            }
            if ($account == NULL) {
                $this->addMessage('error', 'Account not found.');
                $this->index();
                return;
            }
            $this->showTransactionForm($account, $type);
        } else if (in_array($firstParam, $this->genericNewTransactionModes, TRUE)) {
            $type = $firstParam;
            $this->showTransactionForm(NULL, $type);
        } else if (in_array($firstParam, $this->existingTransactionModes, TRUE)) {
            $transactionID = filter_var($this->input->get('transaction-id', TRUE), FILTER_VALIDATE_INT);
            if ($transactionID != FALSE) {//transaction valid ID
                $this->load->model('financial_transactions');
                if ($firstParam == 'edit' || $firstParam == 'view') { //edit or view mode, so load transaction details from database
                    $transaction = $this->financial_transactions->getTransaction($transactionID);
                    if ($transaction != NULL) { //valid transaction found
                        if ($firstParam == 'edit') { //show edit transaction form
                            $this->showTransactionForm($userID, $accountID);
                        } else { //show transaction details form
                        }
                    } else { //transaction not found in database. show invalid transaction error
                        $this->addMessage('error', 'Invalid transaction requested');
                        //TODO redirect to current account page
                    }
                } else { // trash mode
                    $isDeleted = $this->financial_transaction->trashTransaction($transactionID);
                    if ($isDeleted) {
                        $this->addMessage('success', 'Transaction has been moved to trash.');
                    } else {
                        $this->addMessage('error', 'Transaction can not be trashed');
                    }
                    //TODO redirect to current account page
                }
            } else {//invalid transaction
                $this->addMessage('error', 'Invalid transaction requested');
                //TODO redirect to current account page
            }
        } else {
            $this->addMessage('error', 'Page not found');
            //TODO redirect
        }
    }

    private function showTransactionForm($account, $type) {
        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);
        $this->load->helper('form');
        $this->load->model('financial_categories');
        $categories = $this->financial_categories->getCategories($userID);
        $this->showView('financials/new_transaction', array(
            'account' => $account,
            'type' => $type,
            'categories' => $categories
        ));
    }

    private function handleFormsubmit($type) {
        if (in_array($type, $this->newTransactionModes, TRUE)) {

            $accountIDRule = array('account-id', 'Account', 'required|integer|callback_valid_account|xss_clean');
            $secondaryAccountIDRule = array('secondary-account-id', 'Target Account', 'required|integer|callback_valid_account|xss_clean');
            $titleRule = array('title', 'Title', 'required|max_length[50]|xss_clean');
            $descriptionRule = array('description', 'Description', 'max_length[500]|xss_clean');
            $categoryRule = array('category', 'Category', 'required|integer|callback_valid_category|xss_clean');
            $amountRule = array('amount', 'Amount', 'required|numeric|xss_clean');
            $rules['deposite'] = array($accountIDRule, $titleRule, $descriptionRule, $categoryRule, $amountRule);
            $rules['spend'] = $rules['deposite'];
            $rules['transfer'] = array($accountIDRule, $titleRule, $descriptionRule, $secondaryAccountIDRule, $amountRule);


            $this->load->library('form_validation');
            if ($this->form_validation->run($rules[$type]) == FALSE) {
                $this->addMessage('error', $this->form_validation->error_string());
                return false;
            }else{
                $transaction['id'] = $this->input->post('account-id', TRUE);
                $transaction['title'] = $this->input->post('account-id', TRUE);
                $transaction['description'] = $this->input->post('account-id', TRUE);
                $transaction['category-id'] = $this->input->post('account-id', TRUE);
                $transaction['amount'] = $this->input->post('account-id', TRUE);
                $transaction['id'] = $this->input->post('account-id', TRUE);
            }
        }
    }

    private function valid_category($categoryID) {
        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);
        $this->load->model('financial_categories');
        $category = $this->financial_categories->getCategory($userID, $categoryID);
        return $category != NULL;
    }

    private function showTransactionDetails($transactionID) {
        
    }

}
?>
