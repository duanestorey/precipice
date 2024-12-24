<?php
/* 
    Copyright (C) 2024 by Duane Storey - All Rights Reserved
    You may use, distribute and modify this code under the
    terms of the GPLv3 license.
 */

namespace DuaneStorey\Precipice;

// Prevent direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once( PRECIPICE_MAIN_DIR . '/src/github-updater.php' );

class Precipice extends GithubUpdater {
    private static $instance = null;

    protected $settings = null;

    protected function __construct() {
        // initialize the updater
        parent::__construct( 
            'precipice/precipice.php',
            'duanestorey',
            'precipice',
            PRECIPICE_VER,
            'main'
        );
    }

    public function init() {
       // $this->settings->init();
       add_action( 'admin_menu', array( $this, 'removeWordPresDashboard' ), 100 );
       add_action( 'admin_init', array( $this, 'doAdminRedirection' ) );
    }

    public function doAdminRedirection() {
        //echo home_url() . $_SERVER[ 'REQUEST_URI' ] . ' ' . admin_url();  die;
        if (  home_url() . $_SERVER[ 'REQUEST_URI' ] == admin_url() ) {
            header( 'Location: ' . admin_url( 'index.php?page=index.php') );
            die;
        }
    }

    public function outputDashboard() {
        require
    }

    public function removeWordPresDashboard() {
        remove_submenu_page( 'index.php', 'index.php' );

        add_submenu_page(
            'index.php',
            'Precipice',
            'Precipice',
            'manage_options',
            'index.php',
            array( $this, 'outputDashboard' ),
            -1
        );
    }

    static function instance() {
        if ( self::$instance == null ) {
            self::$instance = new Precipice();
        }
        
        return self::$instance;
    }
}
