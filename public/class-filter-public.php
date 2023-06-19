<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FILTER_Public {
    private $theme_name;
    private $version;

    public function __construct( $theme_name, $version ) {
        $this->theme_name = $theme_name;
        $this->version    = $version;
    }

    public function enqueue_styles() {
        
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script( 'filter_public_js', FILTER_DIR_URI . 'public/js/filter_public.js', ['jquery'], filemtime(FILTER_DIR_PATH . 'public/js/filter_public.js'), true );
        $loading = FILTER_DIR_URI . 'public/img/mainbo-load.gif';
		
		if ( 'product' == get_post_type() ){
			global $post;
			$post_id = $post->ID; 
			$list = get_field( "details_product", $post_id );
			//var_dump($list);
		}
		
		if(!isset($list)) $list = [];
		
        $pfolio_Public = [
            'url'     => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'pfolio_seg' ),
            'loading' => $loading,
			'list' => $list,
        ];
        wp_localize_script( 'filter_public_js', 'pfolio_Public', $pfolio_Public );
    }
}