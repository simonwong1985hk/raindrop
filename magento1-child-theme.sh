if [ -z $1 ] || [ -z $2 ] || [ -z $3 ] || [ -z $4 ]; then

    echo '$1 = CHILD_THEME_NAME'
    echo '$2 = DATABASE_NAME'
    echo '$3 = DATABASE_USER'
    echo '$4 = DATABASE_PASSWORD'

else

    mkdir -p app/design/frontend/$1/default

    mkdir -p app/design/frontend/$1/default/etc

    echo '<?xml version="1.0"?>
<theme>
    <parent>rwd/default</parent>
</theme>' > app/design/frontend/$1/default/etc/theme.xml

    mkdir -p app/design/frontend/$1/default/layout

    mkdir -p app/design/frontend/$1/default/template

    mkdir -p skin/frontend/$1/default

    cp -R skin/frontend/rwd/default/images skin/frontend/$1/default

    cp -R skin/frontend/rwd/default/scss skin/frontend/$1/default

    echo 'sourcemap = true' >> skin/frontend/$1/default/scss/config.rb

    sed -i '' 's/production/development/g' skin/frontend/$1/default/scss/config.rb

    # Disable Cache Management

    mysql -u $3 -p$4 -e "USE $2; UPDATE core_cache_option SET value=0;"

    php -r "require_once('app/Mage.php');umask(0);Mage::app();Mage::getConfig()->saveConfig('design/package/name', '$1', 'default', 0);"

    rm -rf var/cache

    rm -rf skin/frontend/$1/default/images/media
    rm -rf skin/frontend/$1/default/scss/madisonisland.scss
    rm -rf skin/frontend/$1/default/scss/madisonisland-ie8.scss
    rm -rf skin/frontend/$1/default/scss/content/_category.scss
    rm -rf skin/frontend/$1/default/scss/content/_home.scss
    rm -rf skin/frontend/$1/default/css/madisonisland.css
    rm -rf skin/frontend/$1/default/css/madisonisland-ie8.css

    echo '<layout version="0.1.0">
    <default>
        <reference name="head">
            <!-- Removing sample data assets -->
            <action method="removeItem"><type>skin_js</type><name>js/slideshow.js</name></action>
            <action method="removeItem"><type>skin_js</type><name>js/lib/jquery.cycle2.min.js</name></action>
            <action method="removeItem"><type>skin_js</type><name>js/lib/jquery.cycle2.swipe.min.js</name></action>
            <action method="removeItem"><type>skin_css</type><name>css/madisonisland.css</name></action>
            <action method="removeItem"><type>skin_css</type><name>css/madisonisland-ie8.css</name></action>
            <!-- <action method="removeItem"><type>link_rel</type><name>//fonts.googleapis.com/css?family=Raleway:300,400,500,700,600</name></action> -->

            <!-- Google fonts -->
            <!-- <action method="addLinkRel"><rel>stylesheet</rel><href>//fonts.googleapis.com/css?family=NEW_FONT</href></action> -->

            <!-- '"$1"'.css -->
            <action method="addItem">
                <type>skin_css</type>
                <name>'"$1"'/'"$1"'.css</name>
                <params/>
                <if><![CDATA[<!--[if (gte IE 9) | (IEMobile)]><!-->]]></if>
            </action>

            <!-- '"$1"'.js -->
            <action method="addItem">
                <type>skin_js</type>
                <name>'"$1"'/'"$1"'.js</name>
                <params/>
            </action>
        </reference>

        <remove name="footer_links" />
        <remove name="footer_links2" />
    </default>
</layout>' > app/design/frontend/$1/default/layout/local.xml

    mkdir -p skin/frontend/$1/default/$1

    touch skin/frontend/$1/default/$1/$1.css

    touch skin/frontend/$1/default/$1/$1.js

    # sed -i '' 's/Raleway/NEW_FONT/g' skin/frontend/$1/default/scss/_var.scss

    compass compile skin/frontend/$1/default/scss

fi
