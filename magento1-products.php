<?php
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

// Allow display errors
ini_set('display_errors', 1);

// Search products filter by specific attribute
$products = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter([
                    [ 'attribute' => 'name', 'like' => '%ABC%' ],
                    [ 'attribute' => 'name', 'like' => '%XYZ%' ],
                ]);

// Delete products
foreach ($products as $product){
    $product->delete();
    echo $product->getName() . ' is deleted.' . PHP_EOL;

}

echo 'Done.' . PHP_EOL;
