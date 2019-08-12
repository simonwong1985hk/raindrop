if [ ! -z $1 ]; then

	path="./wp-content/themes/$1-child"

	if [ -d $path ]; then
		rm -rf $path
	fi

	mkdir -p $path

	echo "/*
 Theme Name:   $1-child
 Template:     $1
*/" > $path/style.css

	echo "<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    \$parent_style = '$1-style';
    wp_enqueue_style( \$parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( \$parent_style ),
        wp_get_theme()->get('Version')
    );
}" > $path/functions.php

else
	echo '$1 = PARENT_THEME'
fi
