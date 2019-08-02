if [ ! -z $1 ]; then

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

	mysql -u root -p"root" <<EOF

    USE $2;

    UPDATE core_cache_option SET value=0;
EOF

    php -r "require_once('app/Mage.php');umask(0);Mage::app();Mage::getConfig()->saveConfig('design/package/name', '$1', 'default', 0);"

    rm -rf var/cache

    rm -rf skin/frontend/$1/default/images/media
    rm -rf skin/frontend/$1/default/scss/madisonisland.scss
    rm -rf skin/frontend/$1/default/scss/madisonisland-ie8.scss
    rm -rf skin/frontend/$1/default/scss/content/_category.scss
    rm -rf skin/frontend/$1/default/scss/content/_home.scss
    rm -rf skin/frontend/$1/default/css/madisonisland.css
    rm -rf skin/frontend/$1/default/css/madisonisland-ie8.css

    git clone https://github.com/simonwong1985hk/My_External.git

    cp -R My_External/* .

    rm -rf My_External

	echo '<layout version="0.1.0">
    <default>
        <!-- Set default page layout to 1 column -->
        <reference name="root">
            <action method="setTemplate"><template>page/1column.phtml</template></action>
        </reference>

        <reference name="head">
            <!-- Removing sample data assets -->
            <action method="removeItem"><type>skin_js</type><name>js/slideshow.js</name></action>
            <action method="removeItem"><type>skin_js</type><name>js/lib/jquery.cycle2.min.js</name></action>
            <action method="removeItem"><type>skin_js</type><name>js/lib/jquery.cycle2.swipe.min.js</name></action>
            <action method="removeItem"><type>skin_css</type><name>css/madisonisland.css</name></action>
            <action method="removeItem"><type>skin_css</type><name>css/madisonisland-ie8.css</name></action>
            <action method="removeItem"><type>link_rel</type><name>//fonts.googleapis.com/css?family=Raleway:300,400,500,700,600</name></action>

            <!-- Google fonts -->
            <action method="addLinkRel"><rel>stylesheet</rel><href>//fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i|Noto+Serif:400,400i,700,700i</href></action>

            <!-- uikit.min.css -->
            <action method="addExternalItem">
                <type>external_css</type>
                <name>https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/css/uikit.min.css</name>
                <params/>
            </action>

            <!-- uikit.min.js -->
            <action method="addExternalItem">
                <type>external_js</type>
                <name>https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/js/uikit.min.js</name>
                <params/>
            </action>

            <!-- slideshow.min.js -->
            <action method="addExternalItem">
                <type>external_js</type>
                <name>https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/js/components/slideshow.min.js</name>
                <params/>
            </action>

            <!-- slider.min.js -->
            <action method="addExternalItem">
                <type>external_js</type>
                <name>https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/js/components/slider.min.js</name>
                <params/>
            </action>

            <!-- font-awesome.min.css -->
            <action method="addExternalItem">
                <type>external_css</type>
                <name>https://use.fontawesome.com/releases/v5.3.1/css/all.css</name>
                <params/>
            </action>

            <!-- custom.css -->
            <action method="addItem">
                <type>skin_css</type>
                <name>custom/custom.css</name>
                <params/>
                <if><![CDATA[<!--[if (gte IE 9) | (IEMobile)]><!-->]]></if>
            </action>

            <!-- custom.js -->
            <action method="addItem">
                <type>skin_js</type>
                <name>custom/custom.js</name>
                <params/>
            </action>
        </reference>

        <remove name="footer_links" />
        <remove name="footer_links2" />
    </default>
</layout>' > app/design/frontend/$1/default/layout/local.xml

	mkdir -p skin/frontend/$1/default/custom

	touch skin/frontend/$1/default/custom/custom.css

	touch skin/frontend/$1/default/custom/custom.js

	sed -i '' 's/Raleway/Noto Sans/g' skin/frontend/$1/default/scss/_var.scss

	compass compile skin/frontend/$1/default/scss

else

    echo '$1 = YOUR_THEME_NAME'
    echo '$2 = YOUR_DATABASE_NAME'

fi
