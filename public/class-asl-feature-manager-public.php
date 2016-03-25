<?php

class ASL_Feature_Manager_Public {

	protected $version;

	public function __construct( $version ) {
		$this->version = $version;
	}

	/*public function enqueue_unique_styles() {
		if (!is_admin()) {
		    wp_register_style( 'asl-feature-manager-stylesheet', '//sherman.library.nova.edu/cdn/styles/css/public-global/s--apa.css', array( 'legacy-css' ), '1.1', 'all' );
		    wp_enqueue_style( 'asl-feature-manager-stylesheet' );
		}
	}

	public function enqueue_unique_scripts() {
		if (!is_admin()) {
		    wp_register_script( 'asl-feature-manager-script', plugins_url( 'asl-feature-manager/public/scripts/asl-feature-manager-scripts.js' ), array( 'pls-js' ), '0.1', true );
		    wp_enqueue_script( 'asl-feature-manager-script' );

		    wp_localize_script( 'asl-feature-manager-script', 'ASL_Feature_Manager_search', array(
		    	'ajax_url' => admin_url( 'admin-ajax.php' )
	    	));
		}
	}*/

	/*public function replace_category_archive_template( $category ) {

		if ( is_category() ) {
			return plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/category.php';
		}

		return $category;
	}*/
	/*
	public function create_single_formatting_view( $single ) {
		global $wp_query, $post;

		if ( $post->post_type =='formatting' ) {

			return plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/single-formatting.php';

		}

		return $single;
	}*/

}
?>
