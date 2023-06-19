<?php 
class FILTER_Admin {
    private $theme_name;
    private $version;
    private $build_menupage;
    
    public function __construct( $theme_name, $version ) {
        $this->theme_name     = $theme_name;
        $this->version        = $version;
        $this->build_menupage = new FILTER_Build_Menupage();
    }
    
    public function enqueue_styles( $hook ) {
        
    }
    public function enqueue_scripts( $hook ) {
        
    }
}