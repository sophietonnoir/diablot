<?php
/*
Plugin Name: Google Analytics Counter Tracker

Description: Google analytics counter tracker - analyse the visitors hits on you website and display it graphically
Version: 3.0.3
Author: WPAdm
Author URI: http://www.wpadm.com
Plugin URI: http://www.wpadm.com
License: GPLv2 or later
*/

define( 'WPADM_GA__VIEW_TITLE', 'Google Analytics Counter');
define( 'WPADM_GA__PLUGIN_NAME', basename(dirname(__FILE__)) );

define( 'WPADM_GA__MENU_PREFIX', 'wpadm-ga-menu-' );
define( 'WPADM_GA__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPADM_GA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPADM_GA__VIEW_DIR', plugin_dir_path( __FILE__ ) . 'view' . DIRECTORY_SEPARATOR );
define( 'WPADM_GA__VIEW_LAYOUT', WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'layout.php');

define( 'WPADM_GA__DB_VERSION', 1);

add_action('init', array( 'Wpadm_GA', 'init'));
add_action('admin_menu', array( 'Wpadm_GA', 'generateMenu'));

register_activation_hook(__FILE__, array( 'Wpadm_GA', 'plugin_activation'));
register_deactivation_hook(__FILE__, array( 'Wpadm_GA', 'plugin_deactivation'));

register_uninstall_hook(__FILE__, array( 'Wpadm_GA', 'plugin_uninstall'));

add_action( 'admin_enqueue_scripts', array( 'Wpadm_GA', 'registerPluginScripts') );

add_action('wp_footer', array('Wpadm_GA', 'generateGACodeOnSite'));

add_action( 'wp_ajax_getCache', array('Wpadm_GA_Cache', 'getCache') );

add_action( 'wp_ajax_setCache', array('Wpadm_GA', 'setDtStartWork') );
add_action( 'wp_ajax_setCache', array('Wpadm_GA_Cache', 'setCache') );

add_action( 'wp_ajax_sendSupport', array('Wpadm_GA', 'sendSupport') );

add_action( 'wp_ajax_stopNotice5Stars', array('Wpadm_GA', 'stopNotice5Stars') );

require_once( WPADM_GA__PLUGIN_DIR . 'class.wpadm-ga.php' );

