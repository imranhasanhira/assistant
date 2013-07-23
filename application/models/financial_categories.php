<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Financial_categories extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCategories($userID = NULL) {
        return $this->getUserDefinedCategories($userID) + $this->getSystemCategories();
    }

    public function getUserDefinedCategories($userID = NULL) {
        if ($userID != NULL) {
            $categories['6'] = 'Study';
            $categories['7'] = 'Transport';
            $categories['8'] = 'Food';
            $categories['9'] = 'Bazar';
            $categories['10'] = 'Shopping';
            return $categories;
        }
        return NULL;
    }

    public function getSystemCategories() {
        $categories['1'] = 'Borrow';
        $categories['2'] = 'Pay-Back';
        $categories['3'] = 'Lend';
        $categories['4'] = 'Lend-Back';
        return $categories;
    }

    public function getUserDefinedCategory($userID, $categoryID) {
        foreach ($this->getUserDefinedCategories($userID) as $catID => $categoryName) {
            if ($catID == $categoryID) {
                return array('id' => $categoryID, 'name' => $categoryName);
            }
        }
        return NULL;
    }

    public function isUserDefinedCategory($userID, $categoryID) {
        return $this->getUserDefinedCategory($userID, $categoryID) != NULL;
    }

    public function isSystemCategory($categoryID) {
        return array_key_exists($categoryID, $this->getSystemCategories());
    }

}

?>
