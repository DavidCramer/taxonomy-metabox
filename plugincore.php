<?php
/**
 * @package   Taxonomy_Metabox
 * @author  David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 David Cramer
 *
 * @wordpress-plugin
 * Plugin Name: Taxomony Metabox
 * Plugin URI:  http://cramer.co.za
 * Description: Unify all post taxonomies into a single, tabbed metabox.
 * Version:	1.0.0
 * Author:	David Cramer
 * Author URI:  http://digilab.co.za/
 * Text Domain: taxonomy-metabox
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TAXMETA_PATH',  plugin_dir_path( __FILE__ ) );
define( 'TAXMETA_CORE',  __FILE__ );
define( 'TAXMETA_URL',  plugin_dir_url( __FILE__ ) );
define( 'TAXMETA_VER', '1.0.0' );

include_once TAXMETA_PATH . 'classes/class-taxonomy-metabox.php';


// Load instance
add_action( 'plugins_loaded', array('Taxonomy_metabox', 'get_instance') );