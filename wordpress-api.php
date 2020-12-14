<?php
/**
 * Custome Post Type UI
 * https://wordpress.org/plugins/custom-post-type-ui/
 */

/**
 * Advanced Custom Fields
 * https://wordpress.org/plugins/advanced-custom-fields/
 */

/**
 * Expose all ACF fields to REST API (post)
 */
function create_acf_meta_in_rest() {
	$postypes_to_exclude = ['acf-field-group','acf-field'];

	$extra_postypes_to_include = ['page'];

	$post_types = array_diff(get_post_types(['_builtin' => false], 'names'),$postypes_to_exclude);

	array_push($post_types, $extra_postypes_to_include);

	foreach ($post_types as $post_type) {
		register_rest_field( $post_type, 'acf', [
			'get_callback'    => 'expose_acf_fields',
			'schema'          => null,
		]);
	}
}

function expose_acf_fields($object) {
	$id = $object['id'];
	return get_fields($id);
}

add_action( 'rest_api_init', 'create_acf_meta_in_rest' );

/**
 * Expose a ACF field to REST API (post)
 */
function register_FIELDNAME_api_field()
{
    register_rest_field('photographer', 'FIELDNAME',
        array(
            'get_callback' => 'get_FIELDNAME_api_field',
            'schema' => null,
        )
    );
}

function get_FIELDNAME_api_field($post)
{
	return get_field('FIELDNAME', $post['id']);
}

add_action('rest_api_init', 'register_FIELDNAME_api_field');

/**
 * Expose a ACF field to REST API (taxonomy)
 */
function register_FIELDNAME_api_field()
{
    register_rest_field('photographer', 'FIELDNAME',
        array(
            'get_callback' => 'get_FIELDNAME_api_field',
            'schema' => null,
        )
    );
}

function get_FIELDNAME_api_field($post)
{
	return get_field('FIELDNAME', $post['taxonomy'] . '_' . $post['id']);
}

add_action('rest_api_init', 'register_FIELDNAME_api_field');


/**
 * https://api.your-domain.com/wp-json/wp/v2/posts
 * https://api.your-domain.com/wp-json/wp/v2/posts?_fields=field1,field2,field3
 */
 
