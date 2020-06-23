<?php

/**
 * Delete catalog search terms
 */

// Check PHP version
if (version_compare(phpversion(), '5.3.0', '<')===true) {
    echo  'Magento supports PHP 5.3.0 or newer.';
    exit;
}

// Define app root path
define('MAGENTO_ROOT', getcwd());

// Get Mage.php path
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';

// Include required files
require MAGENTO_ROOT . '/app/bootstrap.php';
require_once $mageFilename;

// Get initialized application object.
Mage::app();

// Solved "Cannot complete this operation from non-admin area"
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

// Set enabled developer mode
Mage::setIsDeveloperMode(true);

ini_set('display_errors', 1);

// Get catalog search terms collection
$search_terms = Mage::getModel('catalogsearch/query')->getCollection();

$count = count($search_terms);

foreach ($search_terms as $search_term) {
    $search_term->delete();
}

echo "$count search terms have been deleted" . PHP_EOL;
