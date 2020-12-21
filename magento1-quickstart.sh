#!/bin/bash

# prerequisite
domain=''
docroot=''
db_name=''
db_user=''
db_pass=''
admin_user=''
admin_pass=''
admin_email=''
theme_name=''
cron_email=''

# change to docroot
cd $docroot

# delete all files
rm -rf {.,}*

# download magento
git clone https://github.com/simonwong1985hk/magento1-demo.git .

# import sample data
mysql -u$db_user -p$db_pass <<EOF
DROP DATABASE IF EXISTS $db_name;
CREATE DATABASE IF NOT EXISTS $db_name;
USE $db_name;
SOURCE magento_sample_data_for_1.9.2.4.sql;
EXIT
EOF
echo 'Sample data imported.'

# install magento
/usr/local/bin/ea-php73 -d memory_limit=-1 -f install.php -- \
--license_agreement_accepted yes \
--locale en_US \
--timezone Asia/Hong_Kong \
--default_currency HKD \
--db_host localhost \
--db_name $db_name \
--db_user $db_user \
--db_pass $db_pass \
--db_prefix '' \
--url https://$domain/ \
--skip_url_validation yes \
--use_rewrites yes \
--use_secure yes \
--secure_base_url https://$domain/ \
--use_secure_admin yes \
--enable_charts \
--admin_lastname $admin_user \
--admin_firstname $admin_user \
--admin_email $admin_email \
--admin_username $admin_user \
--admin_password $admin_pass \
--encryption_key ''

# make app/.../default
mkdir -p app/design/frontend/$theme_name/default

# make app/.../etc
mkdir -p app/design/frontend/$theme_name/default/etc

# create app/.../theme.xml
echo '<?xml version="1.0"?>
<theme>
<parent>rwd/default</parent>
</theme>' > app/design/frontend/$theme_name/default/etc/theme.xml

# make app/.../layout
mkdir -p app/design/frontend/$theme_name/default/layout

# make app/.../template
mkdir -p app/design/frontend/$theme_name/default/template

# make skin/.../default
mkdir -p skin/frontend/$theme_name/default

# inherit from parent rwd
cp -R skin/frontend/rwd/default/images skin/frontend/$theme_name/default

# inherit from parent rwd
cp -R skin/frontend/rwd/default/scss skin/frontend/$theme_name/default

# config scss
echo 'sourcemap = true' >> skin/frontend/$theme_name/default/scss/config.rb

# change scss to development mode
sed -i 's/production/development/g' skin/frontend/$theme_name/default/scss/config.rb

# disable cache
mysql -u$db_user -p$db_pass -e "USE $db_name; UPDATE core_cache_option SET value=0;"

# apply child theme
/usr/local/bin/ea-php73 -r "require_once('app/Mage.php');umask(0);Mage::app();Mage::getConfig()->saveConfig('design/package/name', '$theme_name', 'default', 0);"

# delete sample data assets
# rm -rf skin/frontend/$theme_name/default/images/media
# rm -rf skin/frontend/$theme_name/default/scss/madisonisland.scss
# rm -rf skin/frontend/$theme_name/default/scss/madisonisland-ie8.scss
# rm -rf skin/frontend/$theme_name/default/scss/content/_category.scss
# rm -rf skin/frontend/$theme_name/default/scss/content/_home.scss
# rm -rf skin/frontend/$theme_name/default/css/madisonisland.css
# rm -rf skin/frontend/$theme_name/default/css/madisonisland-ie8.css

# create app/design/frontend/$theme_name/default/layout/local.xml
echo '<layout version="0.1.0">
<default>
    <reference name="head">
        <!-- Removing sample data assets -->
        <!-- <action method="removeItem"><type>skin_js</type><name>js/slideshow.js</name></action> -->
        <!-- <action method="removeItem"><type>skin_js</type><name>js/lib/jquery.cycle2.min.js</name></action> -->
        <!-- <action method="removeItem"><type>skin_js</type><name>js/lib/jquery.cycle2.swipe.min.js</name></action> -->
        <!-- <action method="removeItem"><type>skin_css</type><name>css/madisonisland.css</name></action> -->
        <!-- <action method="removeItem"><type>skin_css</type><name>css/madisonisland-ie8.css</name></action> -->
        <!-- <action method="removeItem"><type>link_rel</type><name>//fonts.googleapis.com/css?family=Raleway:300,400,500,700,600</name></action> -->

        <!-- Google fonts -->
        <!-- <action method="addLinkRel"><rel>stylesheet</rel><href>//fonts.googleapis.com/css?family=NEW_FONT</href></action> -->

        <!-- '"$theme_name"'.css -->
        <action method="addItem">
            <type>skin_css</type>
            <name>'"$theme_name"'/'"$theme_name"'.css</name>
            <params/>
            <if><![CDATA[<!--[if (gte IE 9) | (IEMobile)]><!-->]]></if>
        </action>

        <!-- '"$theme_name"'.js -->
        <action method="addItem">
            <type>skin_js</type>
            <name>'"$theme_name"'/'"$theme_name"'.js</name>
            <params/>
        </action>
    </reference>

    <!-- <remove name="footer_links" /> -->
    <!-- <remove name="footer_links2" /> -->
</default>
</layout>' > app/design/frontend/$theme_name/default/layout/local.xml

# make skin/frontend/$theme_name/default/custom
mkdir -p skin/frontend/$theme_name/default/$theme_name

# create custom css
touch skin/frontend/$theme_name/default/$theme_name/$theme_name.css

# create custom js
touch skin/frontend/$theme_name/default/$theme_name/$theme_name.js

# change font
# sed -i 's/Raleway/NEW_FONT/g' skin/frontend/$theme_name/default/scss/_var.scss

# compile scss
compass compile skin/frontend/$theme_name/default/scss

# reindex
/usr/local/bin/ea-php73 -f shell/indexer.php reindexall

# flush cache
rm -rf var/cache

# set cron jobs
crontab -r
crontab -l > mycron
echo 'MAILTO='"$cron_email"'' >> mycron
echo 'SHELL="/bin/bash"' >> mycron
echo '*/5 * * * * /usr/local/bin/ea-php73 '"$docroot"'cron.php >/dev/null 2>&1' >> mycron
crontab mycron
rm mycron

# execute bash script
exec bash
