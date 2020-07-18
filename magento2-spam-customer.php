<?php

/**
 * Delete spam customers
 */

require __DIR__ . '/app/bootstrap.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();

// Allow delete operation
$objectManager->get('Magento\Framework\Registry')->register('isSecureArea', true);

$customerFactory = $objectManager->create(\Magento\Customer\Model\CustomerFactory::class);

$customers = $customerFactory->create()->getCollection()
				->addAttributeToSelect('*')
				->addAttributeToFilter([
					[ 'attribute' => 'email', 'like' => '%.ru' ],
					[ 'attribute' => 'firstname', 'like' => '%http%' ],
					[ 'attribute' => 'lastname', 'like' => '%http%' ],
				]);

foreach ($customers as $customer) {
    echo "{$customer->getId()} => {$customer->getEmail()}" . PHP_EOL;
    // $customer->delete();
}

echo count($customers) . " spam customers have been found" . PHP_EOL;
