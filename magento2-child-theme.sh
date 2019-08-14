if [ -z $1 ] || [ -z $2 ] || [ -z $3 ] || [ -z $4 ]; then

    echo '$1 = CHILD_THEME_NAME'
    echo '$2 = DATABASE_NAME'
    echo '$3 = DATABASE_USER'
    echo '$4 = DATABASE_PASSWORD'

else

    # php bin/magento deploy:mode:set developer

    mkdir -p app/design/frontend/$1/$1

    path=app/design/frontend/$1/$1

    # theme.xml
    echo '<theme xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Config/etc/theme.xsd">
     <title>'"$1"'</title>
     <parent>Magento/blank</parent>
</theme>' > $path/theme.xml

    # composer.json
    echo '{
    "name": "'$1'/theme-frontend-blank-child",
    "description": "'$1'",
    "config": {
        "sort-packages": true
    },
    "require": {
        "php": "~7.1.3||~7.2.0",
        "magento/framework": "*",
        "magento/theme-frontend-blank": "*"
    },
    "type": "magento2-theme",
    "license": [
        "OSL-3.0",
        "AFL-3.0"
    ],
    "autoload": {
        "files": [
            "registration.php"
        ]
    }
}' > $path/composer.json

    # registration.php
    echo "<?php
use \Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::THEME, 'frontend/$1/$1', __DIR__);" > $path/registration.php


    # Directories
    mkdir -p $path/web/css/source

    mkdir -p $path/web/fonts

    mkdir -p $path/web/images

    mkdir -p $path/web/js

    # Clear Cache
    rm -rf pub/static/*

    rm -rf var/view_preprocessed/*

    # Declaring Theme Logo
    mkdir -p $path/Magento_Theme/layout

    echo '<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceBlock name="logo">
            <arguments>
                <argument name="logo_file" xsi:type="string">images/logo.png</argument>
                <argument name="logo_img_width" xsi:type="number">300</argument>
                <argument name="logo_img_height" xsi:type="number">300</argument>
            </arguments>
        </referenceBlock>
    </body>
</page>' > $path/Magento_Theme/layout/default.xml

    # Set Child Theme
    mysql -u $3 -p$4 -e "USE $2; UPDATE core_config_data SET value = 4 WHERE path = 'design/theme/theme_id';"

    php bin/magento cache:flush

fi
