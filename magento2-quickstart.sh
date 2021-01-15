#!/bin/bash

# prerequisites
domain=''
docroot=''
vendor_name=''
theme_name=''
theme_title=''
db_name=''
db_user=''
db_pass=''
admin_user=''
admin_pass=''
admin_email=''
cron_email=''

# check if a directory does not exist
if [ ! -d $docroot ] 
then
    echo "no such directory $docroot"
    exit
fi

# change to docroot
cd $docroot

# delete all files
rm -rf {.,}* 2> /dev/null

# download the latest version
/usr/local/bin/ea-php74 -d memory_limit=-1 -f /opt/cpanel/composer/bin/composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition .

# or download a specified version
# /usr/local/bin/ea-php72 -d memory_limit=-1 /opt/cpanel/composer/bin/composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition=2.3.2 .

# create a database
mysql -u$db_user -p$db_pass <<EOF
DROP DATABASE IF EXISTS $db_name;
CREATE DATABASE IF NOT EXISTS $db_name;
EXIT
EOF

# install
/usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento setup:install \
--base-url=https://$domain \
--backend-frontname=admin \
--db-host=localhost \
--db-name=$db_name \
--db-user=$db_user \
--db-password=$db_pass \
--admin-firstname=$admin_user \
--admin-lastname=$admin_user \
--admin-email=$admin_email \
--admin-user=$admin_user \
--admin-password=$admin_pass \
--language=en_US \
--currency=HKD \
--timezone=Asia/Hong_Kong \
--use-rewrites=1

# create a theme directory
mkdir -p app/design/frontend/$vendor_name/$theme_name

# create a theme path variable
theme_path=app/design/frontend/$vendor_name/$theme_name

# declare your theme
echo '<theme xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Config/etc/theme.xsd">
 <title>'"$theme_title"'</title>
 <parent>Magento/luma</parent>
</theme>' > $theme_path/theme.xml

# make your theme a composer package
echo '{
"name": "'$vendor_name'/'$theme_name'",
"description": "'$vendor_name'",
"config": {
    "sort-packages": true
},
"require": {
    "php": "~7.4.0",
    "magento/framework": "*",
    "magento/theme-frontend-luma": "*"
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
}' > $theme_path/composer.json

# add registration.php
echo "<?php
use \Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::THEME, 'frontend/$vendor_name/$theme_name', __DIR__);" > $theme_path/registration.php

# configure images
# theme_path/etc/view.xml

# create directories for static files
mkdir -p $theme_path/web/css/source
mkdir -p $theme_path/web/fonts
mkdir -p $theme_path/web/images
mkdir -p $theme_path/web/js

# create a logo file
touch $theme_path/web/images/logo.png

# to clear the pub/static directory
rm -rf pub/static/*

# to clear the var/view_preprocessed directory:
rm -rf var/view_preprocessed/*

# declaring theme logo
mkdir -p $theme_path/Magento_Theme/layout

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
</page>' > $theme_path/Magento_Theme/layout/default.xml

# applying a theme
mysql -u$db_user -p$db_pass -e "USE $db_name; INSERT INTO core_config_data (scope, scope_id, path, value) VALUES ('default', 0, 'design/theme/theme_id', '4') ON DUPLICATE KEY UPDATE value = '4';"

# disable Magento_TwoFactorAuth
/usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento module:disable Magento_TwoFactorAuth

# change mode
/usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento deploy:mode:set developer

# deploy sample data
# /usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento sampledata:deploy

# upgrade database
/usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento setup:upgrade

# reindex
/usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento indexer:reindex

# flush cache
/usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento cache:flush

# show version
/usr/local/bin/ea-php74 -d memory_limit=-1 -f bin/magento --version

# set cron jobs
crontab -r 2> /dev/null
crontab -l > mycron
echo 'MAILTO='"$cron_email"'' >> mycron
echo 'SHELL="/bin/bash"' >> mycron
echo '*/5 * * * * /usr/local/bin/ea-php74 '"$docroot"'bin/magento cron:run >/dev/null 2>&1' >> mycron
echo '*/5 * * * * /usr/local/bin/ea-php74 '"$docroot"'update/cron.php >/dev/null 2>&1' >> mycron
echo '*/5 * * * * /usr/local/bin/ea-php74 '"$docroot"'bin/magento setup:cron:run >/dev/null 2>&1' >> mycron
crontab mycron
rm mycron

# execute bash script
exec bash
