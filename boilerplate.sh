#!/bin/bash

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="#" class="uk-primary">link color should be black</a>
</body>
</html>' >index.php

mkdir -p assets/css/vendor

mkdir -p assets/images

mkdir -p assets/js

touch style.css

echo '// 1. Your custom variables and variable overwrites.
$global-link-color: #000;
// 2. Import default variables and available mixins.
@import "assets/css/vendor/uikit/src/scss/variables.scss";
@import "assets/css/vendor/uikit/src/scss/mixins.scss";
// 3. Your custom mixin overwrites.
@mixin hook-card() { color: #000; }
// 4. Import UIkit.
@import "assets/css/vendor/uikit/src/scss/uikit.scss";
' >style.scss

cd assets/css/

git clone git://github.com/uikit/uikit.git vendor/uikit

cd ../..

sass --watch style.scss style.css
