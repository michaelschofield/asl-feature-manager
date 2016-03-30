<?php

class ASL_Feature_Manager_Public {

	protected $version;

	public function __construct( $version ) {
		$this->version = $version;
	}

	/**
	 * Expose a feature's meta fields to the WP REST API (v2)
	 *
	 * @param $post_type
	 * @param $attribute: the name of the field.
	 * @param $args: an array with keys that define the callback functions
	 * @url http://v2.wp-api.org/extending/modifying/
	 */
	 public function expose_feature_meta_to_api() {

		 register_rest_field( 'asl-feature', 'asl_feature_link', array(

			 'get_callback' => array(&$this, 'return_feature_metas_callback')

		 ));

		 register_rest_field( 'asl-feature', 'asl_feature_publish_start_date', array(

			 'get_callback' => array(&$this, 'return_feature_metas_callback')

		 ));

		 register_rest_field( 'asl-feature', 'asl_feature_publish_end_date', array(

			 'get_callback' => array(&$this, 'return_feature_metas_callback')

		 ));

		 register_rest_field( 'asl-feature', 'asl_feature_media', array(

			 'get_callback' => array(&$this, 'return_feature_metas_callback')

		 ));

	 }

	 /**
	  * Get the value of the asl_feature_link field
		*
		* @param array $object Details of current post
		* @param string $field_name name of field
		* @param WP_REST_Request $request Current request
		*
		* @return mixed
		*/
	 public function return_feature_metas_callback( $object, $field_name, $request ) {
		 return get_post_meta( $object[ 'id' ], $field_name, true );
	 }

	/**
	 * Expose the library-audience taxonomy to the REST API
	 */
	public function expose_library_audience_taxonomy_to_api() {
		global $wp_taxonomies;

		$taxonomy_name = 'library-audience';

		if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
			$wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
			$wp_taxonomies[ $taxonomy_name ]->rest_base = $taxonomy_name;
			$wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
		}
	}
}
?>
