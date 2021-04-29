https://github.com/qtranslate/qtranslate-xt

# style.css
/*
* Theme Name: My Theme Child
* Author: Theme Author
* Text Domain: my-theme-child
*/

# functions.php
function my_theme_load_theme_textdomain() {
    load_theme_textdomain( 'my-theme-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'my_theme_load_theme_textdomain' );

# *.php
esc_html_e('ENGLISH', 'my-theme-child');

# wp-content/themes/my-theme-child/
wp i18n make-pot . languages/zh_HK.pot
msgid "ENGLISH"
msgstr ""

# wp-content/themes/my-theme-child/
mkdir ../../languages/themes
cp languages/zh_HK.pot ../../languages/themes/my-theme-child-zh_HK.po
msgid "ENGLISH"
msgstr "香港中文"

# wp-content/languages/themes/
msgfmt my-theme-child-zh_HK.po -o my-theme-child-zh_HK.mo
