<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           ASL_Feature_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       ASL Feature Manager
 * Plugin URI:        http://example.com/asl-feature-manager-uri/
 * Description:       Feature Manager is a simple plugin enabling the easy creation and maintenance of "Features" - or ads - on Alvin Sherman Library websites.
 * Version:           0.0.1
 * Author:            Michael Schofield
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       asl-feature-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'core/class-asl-feature-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ASL_Feature_Manager() {

	$plugin = new ASL_Feature_Manager();
	$plugin->run();

}

run_ASL_Feature_Manager();
