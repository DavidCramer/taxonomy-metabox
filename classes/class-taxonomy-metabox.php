<?php
/**
 * Taxonomy Metabox.
 *
 * @package   Taxonomy_Metabox
 * @author  David Cramer <david@CalderaWP.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 David Cramer and CalderaWP LLC
 */

/**
 * Plugin class.
 * @package Taxonomy_Metabox
 * @author  David Cramer <david@CalderaWP.com>
 */
class Taxonomy_Metabox {

	/**
	 * The slug for this plugin
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'taxonomy-metabox';

	/**
	 * Holds class isntance
	 *
	 * @since 0.0.1
	 *
	 * @var      object|Taxonomy_Metabox
	 */
	protected static $instance = null;

	/**
	 * Holds the option screen prefix
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// register metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );

	}


	/**
	 * Return an instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return    object|Taxonomy_Metabox    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, FALSE, basename( TAXMETA_PATH ) . '/languages');

	}
	

	/**
	 * Adds registered metaboxes to wordpress
	 *
	 * @since 0.0.1
	 *
	 */
	public function add_metaboxes() {

		$screen = get_current_screen();
		if( !is_object( $screen ) || $screen->base != 'post' ){
			return;
		}

		global $post;

		// argh!
		wp_deregister_script( 'pods-handlebars' );
		
		// enqueue the styles and scripts for metaboxes
		wp_enqueue_style( 'taxonomy_metabox-baldrick-modals', TAXMETA_URL . '/assets/metabox.css' );
		wp_enqueue_script( 'taxonomy_metabox-post-meta', TAXMETA_URL . '/assets/metabox.js', array( 'media-editor' ) , false, true );

		// get all taxonomies
		$taxonomies = get_object_taxonomies( $post );

		// only add if there are taxonomies.
		if( !empty( $taxonomies ) ){
			add_meta_box(
				'taxonomy_metabox_wrapp',
				__('Taxonomies', $this->plugin_slug),
				array( $this, 'render_metabox' ),
				$screen->post_type
			);
		}

	}


	/**
	 * Renders Metaboxes
	 *
	 * @since 0.0.1
	 *
	 */
	public function render_metabox( $post, $metabox ) {
		
		// get all taxonomies
		$taxonomies = get_object_taxonomies( $post, 'objects' );

		$has_tabs = false;
		if( count( $taxonomies ) > 1 ){
			$has_tabs = 'has-tabs';
		}

		echo '<div id=taxonomy-metabox-' . $metabox['id'] . '" class="taxonomy-metabox-wrapper ' . $has_tabs . '">';
		// check if there are multiu panels (tabs)
		if( !empty( $has_tabs ) ){
			
			// yup- tabs! make 'em
			echo '<span class="taxonomy-metabox-wrapper">';
			echo '<ul class="taxonomy-metabox-tab">';
			// taxonomies
			foreach( $taxonomies as $taxonomy_slug=>$taxonomy ){
				if( $taxonomy_slug == 'post_format'){
					continue;
				}
				$class = '';
				if( empty( $first_tab ) ){
					$class = 'class="active"';
					$first_tab = true;
				}
				echo '<li '. $class . '><a href="#' . $metabox['id'] . '_tab_' . $taxonomy_slug . '">' . $taxonomy->label . '</a></li>';
			}
			echo '</ul>';
			echo '</span>';
		}

		// make tab bodies, even if just a single tab.
		foreach( $taxonomies as $taxonomy_slug => $taxonomy ){
			$display = '';
			if( $taxonomy_slug == 'post_format'){
				continue;
			}
			
			if( !empty( $first_tab ) || empty( $has_tabs ) ){
				$display = 'style="display:block;"';
				$first_tab = false;
			}
			echo '<div id="' . $metabox['id'] . '_tab_' . $taxonomy_slug . '" class="taxonomy-metabox-tab-body" ' . $display . '>';
				if( !empty( $taxonomy->hierarchical ) ){
					echo '<span data-pull="' . $taxonomy_slug . 'div"></span>';
				}else{
					echo '<span data-pull="tagsdiv-' . $taxonomy_slug . '"></span>';
				}
			echo '</div>';
		}

		echo '</div>';
		// control script for height
		?>
		<script>
		var tax_metabox_resize_heights;
		jQuery( function($){
			tax_metabox_resize_heights = function(){
				var box = $('#<?php echo $metabox['id']; ?>'),
					inside = box.find('.inside'),
					tabs = box.find('span.taxonomy-metabox-wrapper');

				inside.css( { minHeight : tabs.outerHeight() } );
			}
			tax_metabox_resize_heights();
			$(window).on('resize', function(){
				tax_metabox_resize_heights()
			});
		});
		</script>
		<?php
	
	}


}















