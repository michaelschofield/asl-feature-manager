<?php

class ASL_Feature_Manager_Admin {

	private $version;

	public function __construct( $version ) {
		$this->version = $version;
	}

	public function enqueue_admin_scripts() {

		if ( is_admin() ) :

			wp_register_script( 'angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js', array(), '1.5.3', true );
			wp_register_script( 'asl-feature-manager-admin-script', plugins_url( 'asl-feature-manager/admin/scripts/asl-feature-manager-admin-scripts.js' ), array( 'jquery' ), '0.1', true );

			wp_enqueue_script( 'angular' );
			wp_enqueue_script( 'asl-feature-manager-admin-script' );

		endif;

	}

	/**
	 * Create and register the asl-feature post type
	 *
	 * @todo Create the "Library Audience" taxonomy if it doesn't exist.
	 */
	public function create_the_feature_post_type() {

		$labels = array(

			'name' => __('Ad Manager' ),
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
			'rewrite'	=> array( 'slug' => 'ad', 'with_front' => false ), /* you can specify it's url slug */
			'has_archive' => 'ads', /* you can rename the slug here */
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
			'taxonomies' => array( 'library-audience' ),
			'supports' => array(
				'excerpt',
				'thumbnail',
				'title'
			),

		 // Expose features through the WP REST API
		 'show_in_rest' => true,
		 'rest_base' => 'features-api',
		 'rest_controller_class' => 'WP_REST_Posts_Controller'
		);

		register_post_type( 'asl-feature', $args );

	}


	public function add_feature_meta_boxes() {
		add_meta_box( 'asl-feature-options', __( 'Feature Manager' ), array(&$this, 'render_feature_options_callback'), 'asl-feature', 'normal', 'high' );
	}

/**
 * Render the meta box, the fields, and the live Preview
 *
 * @todo There's a better way to render this stuff.
 */
	public function render_feature_options_callback( $post ) {

		wp_nonce_field( 'asl_feature_nonce', 'meta_box_nonce' );

		echo '<section ng-app="asl-feature-manager">
			<div style="margin-bottom: 1em;">
				<label for="asl_feature_title" style="display: block;">Change the <strong>Title</strong></label>
				<input class="widefat" name="asl_feature_title" id="asl_feature_title" ng-model="title" ng-init="title=\'' . $post->post_title . '\'" type="text" \>
			</div>
			<div style="margin-bottom: 1em;">
				<label for="asl_feature_excerpt" style="display: block;">Excerpt</label>
				<textarea class="widefat" name="asl_feature_excerpt" id="asl_feature_excerpt" ng-model="excerpt" ng-init="excerpt=\'' . $post->post_excerpt . '\'">{{excerpt}}</textarea>
			</div>
			<div style="margin-bottom: 1em;">
				<label for="asl_feature_media" style="display:block;">Media</label>
				<input type="url" name="asl_feature_media" id="asl_feature_media" ng-model="media" ng-init="media=\'' . wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' )[0] . '\'" />
			</div>
			<div style="margin-bottom: 1em;">
				<label for="asl_feature_link" style="display: block;">Link</label>
				<input class="widefat" type="url" name="asl_feature_link" id="asl_feature_link" ng-model="link" ng-init="link=\'' . get_post_meta( $post->ID, 'asl_feature_link', true ) . '\'" required />
		</div>';

			echo '<div style="margin-bottom: 1em;">';
				echo '<label for="asl_feature_publish_start_date" style="display: block;">Date Up</label>';
				echo '<input class="datepicker" type="date" name="asl_feature_publish_start_date" value="' . get_post_meta( $post->ID, 'asl_feature_publish_start_date', true ) . '" required>';
			echo '</div>';
			echo '<div style="margin-bottom: 1em;">';
				echo '<label for="asl_feature_publish_end_date" style="display: block;">Date Down</label>';
				echo '<input class="datepicker" type="date" name="asl_feature_publish_end_date" value="' . get_post_meta( $post->ID, 'asl_feature_publish_end_date', true ) . '" required>';
			echo '</div>';

			echo
			'<h3>Preview</h3>
			<div style="width: 100%; background-color: #f5f5f5; padding: 3em 0;">
				<article style="width: 400px; background-color: white; border: 1px solid #ddd; border-radius: 0; box-shadow: 0 2px 6px 0 rgba(51,51,51,0.1); overflow: hidden; padding: 1em; position: relative; margin: 0 auto;">
	        <div class="card__media" style="margin-bottom: 1em; padding-bottom: 56.25%; overflow: hidden;">

	          <a ng-href="#">
	            <img ng-if="media" ng-src="{{media}}" style="left: 0; position: absolute; top: 0; vertical-align: bottom; width: 100%;"/>
	            <img src="//placehold.it/500x281" alt="" ng-if="!media" style="left: 0; position: absolute; top: 0; vertical-align: bottom; width: 100%;"/>
	          </a>

	        </div>
	        <header class="card__header">
	          <a href="#" class="link link--undecorated _link-blue" style="text-decoration: none;">
	            <h2 class="delta" style="font-size: 1.5rem; color: #313547; margin-bottom: 0.61875rem; overflow: hidden; text-overflow: ellipsis; padding: 0; white-space: nowrap; font-weight: normal; line-height: 1; font-family: \"Times New Roman\",Times,serif !important;"> {{title}} </h2>
	          </a>
	        </header>
	        <section class="content no-margin" style="margin: 0 !important;">
	          <p style="font-size: 1rem; margin: auto auto 1.41429rem; font-family: \"Times New Roman\",Times,serif !important;""> {{ excerpt }}</p>
	        </section>
	      </article>
			</div>';


		echo '</section>';

	}

/**
 * Validate and save content.
 *
 * @param int the post ID
 */
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
		 * Strip and update the post meta.
		 */
		if ( isset( $_POST[ 'asl_feature_link' ] ) ) {
			update_post_meta( $post_id, 'asl_feature_link', wp_kses( $_POST['asl_feature_link'] ) );
		}

		if ( isset( $_POST['asl_feature_publish_start_date'] ) ) {
			update_post_meta( $post_id, 'asl_feature_publish_start_date', wp_kses( $_POST['asl_feature_publish_start_date'] ) );
		}

		if ( isset( $_POST['asl_feature_publish_end_date'] ) ) {
			update_post_meta( $post_id, 'asl_feature_publish_end_date', wp_kses( $_POST['asl_feature_publish_end_date'] ) );
		}

		if ( isset( $_POST['asl_feature_media'] ) ) {
			update_post_meta( $post_id, 'asl_feature_media', wp_kses( $_POST['asl_feature_media'] ) );
		}


		// To prevent an endless loop, you have to remove and reset the action
		if ( isset( $_POST['asl_feature_excerpt'] ) ) {
			remove_action( 'save_post', array(&$this, 'save_feature_meta_boxes') );
			wp_update_post(
				array(
					'ID' => $post_id,
					'post_excerpt' => wp_kses( $_POST['asl_feature_excerpt'] )
				)
			);
			add_action( 'save_post', array(&$this, 'save_feature_meta_boxes') );
		}

		if ( isset( $_POST['asl_feature_title'] ) ) {
			remove_action( 'save_post', array(&$this, 'save_feature_meta_boxes') );
			wp_update_post(
				array(
					'ID' => $post_id,
					'post_title' => wp_kses( $_POST['asl_feature_title'] )
				)
			);
			add_action( 'save_post', array(&$this, 'save_feature_meta_boxes') );
		}

	}


/**
 * Create custom columns in the Ad Manager dashboard
 */
 	public function create_custom_features_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Heading' ),
			'copy' => __('Ad copy'),
			'publish_date' => __( 'Publish Date' ),
			'unpublish_date' => __( 'Unpublish Date' ),
			'audience' => __('Audience'),
		);

		return $columns;

	}

/**
 * Populate content in the custom columns
 */
	public function manage_custom_feature_columns( $column ) {

		global $post;

		switch( $column ) {

			// Add the excerpt as the ad copy
			case 'copy' :

				if ( empty($post->post_excerpt) ) {
					echo __( '' );
				}

				else {
					echo $post->post_excerpt;
				}
			break;

			// Fetch and format the publish start date meta field
			case 'publish_date' :

				$publish_date = get_post_meta( $post->ID, 'asl_feature_publish_start_date', true );
				if ( empty( $publish_date ) ) {
					echo __( '' );
				}

				else {
					echo date( 'F j, Y', strtotime( $publish_date ) );
				}

			break;

			// Fetch and format the publish end date meta field
			case 'unpublish_date' :

				$unpublish_date = get_post_meta( $post->ID, 'asl_feature_publish_end_date', true );
				if ( empty( $unpublish_date ) ) {
					echo __( '' );
				}
				else {
					echo date( 'F j, Y', strtotime( $unpublish_date ) );
				}

			break;

			// Fetch all the library-audiences ads are targeted to
			case 'audience' :

				$terms = get_the_terms( $post->ID, 'library-audience' );

				if ( !empty( $terms ) ) {
					$out = array();

					foreach( $terms as $term ) {
						$out[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'library-audience' => $term->slug ), 'edit.php' )),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'series', 'display' ) )
						);
					}

					echo join( ', ', $out );
				}

				else {
					echo __( 'No Audience' );
				}
			break;

			default:
			break;
		}

	}

/**
 * This makes the features sortable by publish and unpublish date.
 */
	public function make_feature_columns_sortable( $columns ) {

		$columns = array(
			'publish_date' => __( 'Publish Date'),
			'unpublish_date' => __( 'Unpublish Date' )
		);

		return $columns;
	}
}
