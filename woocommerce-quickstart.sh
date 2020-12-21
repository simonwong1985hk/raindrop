#!/bin/bash

# prerequisite
domain='wc.wowvps5.com'
docroot='/home/wowvps5/public_html/wc/'
db_name='wowvps5_wc'
db_user='wowvps5_user'
db_pass='wowvps5_passw0rd'
admin_user='admin'
admin_pass='woocommerce.Pa$$w0rd'
admin_email='admin@admin.com'

# change to docroot
cd $docroot

# delete all files
rm -rf {.,}*

# download core
/usr/local/bin/ea-php74 /usr/local/bin/wp core download

# configure database
/usr/local/bin/ea-php74 /usr/local/bin/wp core config --dbname=$db_name --dbuser=$db_user --dbpass=$db_pass

# reset database
/usr/local/bin/ea-php74 /usr/local/bin/wp db reset --yes

# install
/usr/local/bin/ea-php74 /usr/local/bin/wp core install --url=https://$domain --title=WooCommerce --admin_user=$admin_user --admin_password=$admin_pass --admin_email=$admin_email

# create .htaccess
echo '# Redirect HTTP to HTTPS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>' >> .htaccess

# change timezone
/usr/local/bin/ea-php74 /usr/local/bin/wp option update timezone_string 'Asia/Hong_Kong'

# set permalink structure
/usr/local/bin/ea-php74 /usr/local/bin/wp rewrite structure '/%category%/%postname%/'

# install storefront
/usr/local/bin/ea-php74 /usr/local/bin/wp theme install storefront

# set up & activate child theme
/usr/local/bin/ea-php74 /usr/local/bin/wp scaffold child-theme storefront-child --parent_theme=storefront --theme_name='Storefront Child' --activate

# set up thumbnail for child theme
cp wp-content/themes/storefront/screenshot.* wp-content/themes/storefront-child/

# delete themes
/usr/local/bin/ea-php74 /usr/local/bin/wp theme delete twentynineteen twentytwenty

# install & activate classic editor
/usr/local/bin/ea-php74 /usr/local/bin/wp plugin install classic-editor --activate

# install & activate woocommerce
/usr/local/bin/ea-php74 /usr/local/bin/wp plugin install woocommerce --activate

# delete plugins
/usr/local/bin/ea-php74 /usr/local/bin/wp plugin delete akismet hello

# show wordpress version
/usr/local/bin/ea-php74 /usr/local/bin/wp core version

# execute bash script
exec bash
