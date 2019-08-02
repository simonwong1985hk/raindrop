<?php

require_once 'app/Mage.php';

$websiteId = Mage::app()->getWebsite()->getId();
$store = Mage::app()->getStore();

$customer = Mage::getModel("customer/customer");
$customer->setWebsiteId($websiteId)
		->setStore($store)
		->setGroupId(1)
		->setPrefix('Prefix')
		->setFirstname('Frist Name')
		->setMiddlename('Middle Name')
		->setLastname('Last Name')
		->setSuffix('Suffix')
		->setEmail('Email Address')
		->setPassword('Password');

try{
    $customer->save();
}
catch (Exception $e) {
    Zend_Debug::dump($e->getMessage());
}

$address = Mage::getModel("customer/address");
$address->setCustomerId($customer->getId())
        ->setFirstname($customer->getFirstname())
        ->setMiddlename($customer->getMiddlename())
        ->setLastname($customer->getLastname())
        ->setCountryId('HK')
        ->setPostcode('Postcode')
        ->setCity('City')
        ->setTelephone('88888888')
        ->setFax('88888888')
        ->setCompany('Company Name')
        ->setStreet('Street')
        ->setIsDefaultBilling('1')
        ->setIsDefaultShipping('1')
        ->setSaveInAddressBook('1');

try{
    $address->save();
}
catch (Exception $e) {
    Zend_Debug::dump($e->getMessage());
}
