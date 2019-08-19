# How to Register a Widget Area
function my_widget_area() {
	register_sidebar( array(
		'name'          => 'WIDGET AREA NAME',
		'id'            => 'widget_area_id',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'my_widget_area' );

# How to display new Widget Areas
<?php if ( is_active_sidebar( 'widget_area_id' ) ) : ?>
	<?php dynamic_sidebar( 'widget_area_id' ); ?>
<?php endif; ?>
