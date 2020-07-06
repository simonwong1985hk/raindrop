<?php

/**
 * composer require khanamiryan/qrcode-detector-decoder
 */

require __DIR__ . "/vendor/autoload.php";

use Zxing\QrReader;

$qrcode = new QrReader('sample.jpg');

echo $qrcode->text();
