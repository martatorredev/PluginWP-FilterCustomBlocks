<?php 
/**
 * Plugin Name:         Filter Products
 * Description:         Custom plugin for filter products.
 * Version:             1.0.0
 * Requires at least:   5.2
 * Requires PHP:        7.0
 * Author:              Marta Torre
 * Author URI:          https://martatorre.dev/
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         filter
 * Domain Path:         /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'WPINC' ) ) {
    die; 
}
global $wpdb;

define( 'FILTER_REALPATH_BASENAME_PLUGIN', dirname( plugin_basename( __FILE__ ) ) . '/' );
define( 'FILTER_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'FILTER_DIR_URI', plugin_dir_url( __FILE__ ) );
define( 'FILTER_VERSION', '1.0.0' );

require_once FILTER_DIR_PATH . 'includes/class-filter-master.php';

function filter_master() {
    $bc_master = new FILTER_Master;
    $bc_master->run();
}

filter_master();