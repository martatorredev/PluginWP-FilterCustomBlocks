<?php 
class FILTER_Master {
    protected $charger;
    protected $theme_name;
    protected $version;
    public function __construct() {
        $this->theme_name = 'FILTER_Theme';
        $this->version = FILTER_VERSION;
        $this->load_dependencies();
        $this->load_instances();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    private function load_dependencies() {
        require_once FILTER_DIR_PATH . 'includes/class-filter-charger.php';        
        require_once FILTER_DIR_PATH . 'includes/class-filter-build-menupage.php';
        require_once FILTER_DIR_PATH . 'admin/class-filter-admin.php';
        require_once FILTER_DIR_PATH . 'public/class-filter-public.php';
        require_once FILTER_DIR_PATH . 'includes/class-filter-gutenberg.php';
        require_once FILTER_DIR_PATH . 'includes/class-filter-restapi.php';
        require_once FILTER_DIR_PATH . 'includes/class-filter-ajax-public.php';

    }
    private function load_instances() {
        $this->charger       = new FILTER_Charger;
        $this->filter_admin  = new FILTER_Admin( $this->get_theme_name(), $this->get_version() );
        $this->filter_public = new FILTER_Public( $this->get_theme_name(), $this->get_version() );
        $this->gutenberg     = new FILTER_Gutenberg;
        $this->restapi       = new LIQUI_Rest_Api;
        $this->ajax_public   = new FILTER_Ajax_Public;
    }
    private function define_admin_hooks() {
        $this->charger->add_action( 'admin_enqueue_scripts', $this->filter_admin, 'enqueue_styles' );
        $this->charger->add_action( 'admin_enqueue_scripts', $this->filter_admin, 'enqueue_scripts' );
        $this->charger->add_action( 'init', $this->gutenberg, 'register_block' );
        $this->charger->add_filter( 'block_categories', $this->gutenberg, 'create_category_gutenberg', 10, 2 );
        $this->charger->add_action( 'rest_api_init', $this->restapi, 'add_field_rest_api');
    }
    private function define_public_hooks() {
        $this->charger->add_action( 'wp_enqueue_scripts', $this->filter_public, 'enqueue_styles' );
        $this->charger->add_action( 'wp_footer', $this->filter_public, 'enqueue_scripts' );

        $this->charger->add_action('wp_ajax_action_loadpfolio', $this->ajax_public, 'loadpfolio');
        $this->charger->add_action('wp_ajax_nopriv_action_loadpfolio', $this->ajax_public, 'loadpfolio');
    }
    public function run() {
        $this->charger->run();
    }
    public function get_theme_name() {
        return $this->theme_name;
    }
    public function get_charger() {
        return $this->charger;
    }
    public function get_version() {
        return $this->version;
    }
}