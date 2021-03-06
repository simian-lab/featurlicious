<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   Featurlicious
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name: Featurlicious
 * Plugin URI:  TODO
 * Description: Description
 * Version:     1.0.0
 * Author:      Nicolás Saiz
 * Author URI:  TODO
 * Text Domain: featurlicious
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-featurlicious.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Featurlicious', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Featurlicious', 'deactivate' ) );

Featurlicious::get_instance();
