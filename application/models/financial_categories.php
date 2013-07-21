<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financial_categories extends CI_Model {

    private $systemCategoryIDs = array(
        2, 4
    );

    public function __construct() {
        parent::__construct();
    }

    public function getCategories($userID = NULL) {
        if ($userID != NULL) {
            $categories['0'] = 'Study';
            $categories['1'] = 'Transport';
            $categories['2'] = 'Food';
            $categories['3'] = 'Bazar';
            $categories['4'] = 'Shopping';
            return $categories;
        }
        return NULL;
    }

    public function getNonSystemCategories($userID) {
        $categories = $this->getCategories($userID);
        foreach ($this->systemCategoryIDs as $systemCategoryID) {
            unset($categories[$systemCategoryID]);
        }
        return $categories;
    }

}

?>
