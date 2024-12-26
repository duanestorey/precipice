<?php
/*
    Plugin Name: Precipice
    Plugin URI: https://github.com/duanestorey/precipice
    Description: A new forward-looking dashboard for the WordPress admin
    Author: Duane Storey
    Author URI: https://duanestorey.com
    Version: 1.0.0
    Stable: 1.0.0
    Requires PHP: 8.0
    Requires at least: 6.7
    Tested up to: 6.7
    Update URI: https://github.com/duanestorey/precipice
    Text Domain: precipice
    Domain Path: /lang
    Primary Branch: main
    GitHub Plugin URI: duanestorey/precipice
    Authority: https://plugins.duanestorey.com

    Copyright (C) 2024-2025 by Duane Storey - All Rights Reserved
    You may use, distribute and modify this code under the
    terms of the GPLv3 license.
*/

namespace DuaneStorey\Precipice;

// Prevent direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'PRECIPICE_VER', '1.0.0' );
define( 'PRECIPICE_MAIN_FILE', __FILE__ );
define( 'PRECIPICE_MAIN_DIR', dirname( __FILE__ ) );

require_once( PRECIPICE_MAIN_DIR . '/src/precipice.php' );

function initialize( $params ) {
    load_plugin_textdomain( 'precipice', false, 'precipice/lang/' );

    Precipice::instance()->init(); 
}

function handle_uninstall() {
    // clean up the options table
    //Settings::deleteAllOptions();
}

add_action( 'init', __NAMESPACE__ . '\initialize' );
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\handle_uninstall' );
