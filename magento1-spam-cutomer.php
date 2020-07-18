<?php

/**
 * Detele spam customers
 *
 * Refer to https://inchoo.net/magento/delete-spam-customer-accounts-magento/
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

// Filter spam customers
$customers = Mage::getModel('customer/customer')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter([
                        [ 'attribute' => 'email', 'like' => '%.au' ],
                        [ 'attribute' => 'email', 'like' => '%.eu' ],
                        [ 'attribute' => 'email', 'like' => '%.de' ],
                        [ 'attribute' => 'email', 'like' => '%.fr' ],
                        [ 'attribute' => 'email', 'like' => '%.kz' ],
                        [ 'attribute' => 'email', 'like' => '%.ru' ],
                        [ 'attribute' => 'email', 'like' => '%.ua' ],
                        [ 'attribute' => 'email', 'like' => '%.ro' ],
                        [ 'attribute' => 'email', 'like' => '%.ru' ],

                        [ 'attribute' => 'firstname', 'like' => '%http%' ],
                        [ 'attribute' => 'firstname', 'regexp' => '[0-9]' ],

                        [ 'attribute' => 'lastname', 'like' => '%http%' ],
                        [ 'attribute' => 'lastname', 'regexp' => '[0-9]' ],
                        [ 'attribute' => 'lastname', 'like' => '%GP' ],
                ]);

$count = count($customers);

foreach ($customers as $customer) {
    // If there is any account with address, it shall be skipped from deleting.
    $customerAddresses = $customer->getAddresses();
    if ($customerAddresses) {
        continue;
    }
    echo $customer->getName() . '<hr />';
    // $customer->delete();
}

echo "$count spam customers have been deleted" . PHP_EOL;
