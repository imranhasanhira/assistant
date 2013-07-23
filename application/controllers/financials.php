<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financials extends MY_Controller {

    private $newTransactionModes = array('deposite', 'spend', 'transfer');
    private $specialTransactionModes = array('borrow', 'pay-back', 'lend', 'lend-back');
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
            $this->index();
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
            $transaction = $this->handleFormSubmit($firstParam);
            if ($transaction != NULL) {
                $this->load->model('financial_transactions');
                $response = FALSE;
                if ($firstParam == 'edit') {
                    $response = $this->financial_transactions->updateTransaction($transaction);
                } else {
                    $response = $this->financial_transactions->insertTransaction($transaction);
                }
                if ($response == FALSE) {
                    $this->addMessage('error', 'Transaction could not be saved.');
                } else {
                    $this->addMessage('success', 'Transaction saved successfully.');
                    $this->account($transaction['account-id']);
                    return;
                }
            }
        }

        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);

        if (in_array($firstParam, $this->newTransactionModes, TRUE)) {
            $type = $firstParam;
            $accountID = filter_var($this->input->post('account-id', TRUE), FILTER_VALIDATE_INT);
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
        } else if (in_array($firstParam, $this->specialTransactionModes, TRUE)) {
            $this->showSpecialTransactionForm($firstParam);
        } else if (in_array($firstParam, $this->existingTransactionModes, TRUE)) {
            $transactionID = filter_var($this->input->post('transaction-id', TRUE), FILTER_VALIDATE_INT);
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
        $this->load->model('financial_accounts');
        $accounts = $this->financial_accounts->getAccounts($userID);
        $this->showView('financials/new_transaction', array(
            'account' => $account,
            'type' => $type,
            'categories' => $categories,
            'accounts' => $accounts
        ));
    }

    private function handleFormSubmit($type) {
        if (in_array($type, $this->newTransactionModes, TRUE) || $type == 'edit') {
            $accountIDRule = array(
                'field' => 'account-id',
                'label' => 'Account',
                'rules' => 'required|integer|callback_valid_account|xss_clean'
            );
            $secondaryAccountIDRule = array(
                'field' => 'secondary-account-id',
                'label' => 'Target Account',
                'rules' => 'required|integer|callback_valid_account|callback_different_than_primary|xss_clean'
            );
            $titleRule = array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'required|max_length[50]|xss_clean'
            );
            $descriptionRule = array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'max_length[500]|xss_clean'
            );
            $categoryRule = array(
                'field' => 'category',
                'label' => 'Category',
                'rules' => 'required|integer|callback_valid_category|xss_clean'
            );
            $amountRule = array(
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'required|numeric|xss_clean'
            );
            $rules['deposite'] = array($accountIDRule, $titleRule, $descriptionRule, $categoryRule, $amountRule);
            $rules['spend'] = $rules['deposite'];
            $rules['transfer'] = array($accountIDRule, $titleRule, $descriptionRule, $secondaryAccountIDRule, $amountRule);


            $this->load->library('form_validation');
            $this->form_validation->set_rules($rules[$type]);
            if ($this->form_validation->run() == FALSE) {
                return NULL;
            } else {

                $transaction['account-id'] = $this->input->post('account-id', TRUE);
                $transaction['title'] = $this->input->post('title', TRUE);
                $transaction['description'] = $this->input->post('description', TRUE);
                $transaction['amount'] = $this->input->post('amount', TRUE);
                if ($type == 'transfer') {
                    $transaction['category-id'] = SYSTEM_CATEGORY_ID_TRANSFER;
                    $transaction['secondary-account-id'] = $this->input->post('secondary-account-id', TRUE);
                } else {
                    $transaction['category-id'] = $this->input->post('category-id', TRUE);
                }
                return $transaction;
            }
        }
    }

    public function valid_category($categoryID) {
        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);
        $this->load->model('financial_categories');
        $isValid = $this->financial_categories->isUserDefinedCategory($userID, $categoryID);
        if (!$isValid) {
            $isValid = $this->financial_categories->isSystemCategory($categoryID);
        }

        if (!$isValid) {
            $this->form_validation->set_message('valid_category', 'Category is required.');
        }
        return $isValid;
    }

    public function valid_account($accountID) {
        $userID = $this->session->userdata(SESSION_LOGGED_IN_USER_ID);
        $this->load->model('financial_accounts');
        $account = $this->financial_accounts->getAccount($userID, $accountID);

        $isValid = $account != NULL;
        if (!$isValid) {
            $this->form_validation->set_message('valid_account', 'Account is not valid.');
        }
        return $isValid;
    }

    public function different_than_primary($secondaryAccountID) {
        $accountID = filter_var($this->input->post('account-id', TRUE), FILTER_VALIDATE_INT);
        $isValid = $accountID != $secondaryAccountID;
        if (!$isValid) {
            $this->form_validation->set_message('different_than_primary', 'Please select a different account.');
        }
        return $isValid;
    }

    private function showTransactionDetails($transactionID) {
        
    }

}

?>
