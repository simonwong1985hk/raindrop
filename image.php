<?php

/**
 * composer require intervention/image
 */

require 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

Image::canvas(800, 600, '#000')->save('sample.jpg');
