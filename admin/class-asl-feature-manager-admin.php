<?php

class ASL_Feature_Manager_Admin {

	private $version;

	public function __construct( $version ) {
		$this->version = $version;
	}

	public function enqueue_admin_scripts() {

		if ( is_admin() ) :

			wp_register_script( 'asl-feature-manager-admin-script', plugins_url( 'asl-feature-manager/admin/scripts/asl-feature-manager-admin-scripts.js' ), array( 'jquery' ), '0.1', true );
			wp_enqueue_script( 'asl-feature-manager-admin-script' );

		endif;

	}

	/**
	 * Create and register the asl-feature post type
	 */
	public function create_the_feature_post_type() {

		$labels = array(

			'name' => __('Ads' ),
			'singular_name' => __('Ad' ),
			'all_items' => __('All Ads' ),
			'add_new' => __('Add New Ad' ),
			'add_new_item' => __('Add New Ad' ),
			'edit' => __( 'Edit'  ),
			'edit_item' => __('Edit Ad' ),
			'new_item' => __('New Ad' ),
			'view_item' => __('View Ad' ),
			'search_items' => __('Search Ads' ),
			'not_found' =>  __('Nothing found.' ),
			'not_found_in_trash' => __('Nothing found in the trash' ),

			'featured_image' => __('Featured Image (500x281)'),
			'set_featured_image' => __('Choose or upload an image (500px wide 281px tall)')
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'show_ui' => true,
				'show_in_nav_menus' => false,
			'query_var' => true,
			'menu_position' => 5,
			'rewrite'	=> array( 'slug' => 'feature', 'with_front' => false ), /* you can specify it's url slug */
			'has_archive' => 'events', /* you can rename the slug here */
			'capability_type' => 'admin',
			/*'capabilities' => array(
			  'edit_post'          => 'edit_feature',
			  'read_post'          => 'read_feature',
			  'delete_post'        => 'delete_feature',
			  'edit_posts'         => 'edit_features',
			  'edit_others_posts'  => 'edit_others_features',
			  'publish_posts'      => 'publish_features',
			  'read_private_posts' => 'read_private_features',
			  'create_posts'       => 'edit_features',
			),*/
			'hierarchical' => false,
			'supports' => array(
				'custom_fields',
				'excerpt',
				'thumbnail',
				'title'
			)
		);

		register_post_type( 'asl-feature', $args );

	}

	public function add_feature_meta_boxes() {
		add_meta_box( 'asl-feature-link', __( 'Link' ), array(&$this, 'render_feature_link_callback'), 'asl-feature', 'normal', 'high' );
	}

	public function render_feature_link_callback( $post ) {

		$values = get_post_custom( $post->ID );
		$link = isset( $values[ 'asl_feature_link' ] ) ? esc_attr( $values['asl_feature_link'][0] ) : '';

		wp_nonce_field( 'asl_feature_nonce', 'meta_box_nonce' );


		echo '<input type="url" name="asl_feature_link" id="asl_feature_link" value=" ' . $link . '" class="widefat" />';
		echo '<input type="text" id="jquery-datepicker" name="asl_feature_publish_start_date" value="' . get_post_meta( $post->ID, 'asl_feature_publish_start_date', true ) . '">';

	}

	public function save_feature_meta_boxes( $post_id ) {

		/**
		 * Let's bail if ...
		 *
		 * Our conditions for bailing are
		 * 1. If we are autosaving
		 * 2. If our nonce doesn't verify
		 * 3. If the user doesn't have the correct permissions to edit
		 */

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'asl_feature_nonce' ) ) return;
		if ( !current_user_can( 'edit_post' ) ) return;

		/**
		 *
		 */
		if ( isset( $_POST[ 'asl_feature_link' ] ) ) {
			update_post_meta( $post_id, 'asl_feature_link', wp_kses( $_POST['asl_feature_link'] ) );
		}

		if ( isset( $_POST['asl_feature_publish_start_date'] ) ) {
			update_post_meta( $post_id, 'asl_feature_publish_start_date', wp_kses( $_POST['asl_feature_publish_start_date'] ) );
		}


	}

}
