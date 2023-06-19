<?php
function restapi_image_featured($object, $field_name, $request) {
    if($object['featured_media']) {
        $imagen = wp_get_attachment_image_src( $object['featured_media'], 'thumbnail' );
        return $imagen[0];
    }
    return false;
}

class LIQUI_Rest_Api{
	public function add_field_rest_api() {
	    register_rest_field( 
	        array('product', 'post'),
	        'featured_image', 
	        array(
				'get_callback'    => 'restapi_image_featured',
				'update_callback' => null,
				'schema'          => null
	        )
	    );
	}
}