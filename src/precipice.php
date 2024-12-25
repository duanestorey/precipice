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

    protected $tiles = [];

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

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueScripts' ) );
    }

    public function init() {
       // $this->settings->init();
       add_action( 'admin_menu', array( $this, 'removeWordPresDashboard' ), 100 );
       add_action( 'admin_init', array( $this, 'doAdminRedirection' ) );
       add_action( 'precipice_setup_tiles', array( $this, 'setupTiles' ) );
    }

    public function enqueueScripts( $page ) {
        if ( $page == 'toplevel_page_index' ) {
            wp_enqueue_style( 'precipice-admin', plugins_url( 'dist/precipice-admin.css', PRECIPICE_MAIN_FILE ), false, time() );
            wp_enqueue_script( 'precipice-admin', plugins_url( 'dist/precipice-admin.js', PRECIPICE_MAIN_FILE ), array( 'jquery' ), time() );
        }
    }

    public function addTile( 
        $unique, 
        $name,
        $bigText,
        $listHeadings,
        $listData,
        $trend = 'none',
        $emphasis = 'normal',
        $big = 0,
        $priority = 0,
      
    ) {
        $tile = new \stdClass;
        $tile->slug = $unique;
        $tile->name = $name;
        $tile->big_text = $bigText;
        $tile->list_headings = $listHeadings;
        $tile->list_data = $listData;
        $tile->trend = $trend;
        $tile->emphasis = $emphasis;
        $tile->priority = $priority;
        $tile->big = $big;

        if ( !isset( $this->tiles[ $priority ] ) ) {
            $this->tiles[ $priority ] = [];
        }

        $this->tiles[ $priority ][] = $tile;
    }

    public function setupTiles( $precipace ) {

        $this->addTile( 
            'sales',
            __( 'Sales', 'precipace' ),
            '$' . sprintf( "%0.2f", 0 ),
            array( __( 'Product', 'precipice' ), __( 'Revenue', 'precipace' ) ),
            array( 
                array( 'My Plugin', '$' . sprintf( '%0.2f', 1252.40 ) ),
                array( 'My Second', '$' . sprintf( '%0.2f', 152.40 ) ),
                array( 'My Third', '$' . sprintf( '%0.2f', 125.40 ) ),
            )
        );

        $users = get_users();
        $count = 0;
        $user_data = [];
        foreach( $users as $user ) {
            if ( $count > 5 ) {
                break;
            }

            $user_data[] = [ $user->display_name, date( 'M jS, Y') ];
            $count++;
        }
        $this->addTile( 
            'last_active',
            __( 'Active Users', 'precipace' ),
            number_format( count( $users ) ),
            array( __( 'User', 'precipice' ), __( 'Date', 'precipace' ) ),
            $user_data
        );

        $comments = get_comments();
        $commentData = [];
        $count = 0;
        foreach( $comments as $oneComment ) {
            if ( $count > 5 ) {
                break;
            }

            $post = get_post( $oneComment->comment_post_ID, $oneComment->post_title );

           // print_r( $post );
            $commentData[] = [ $oneComment->comment_author, $post->post_title, date( 'M jS g:ia', $oneComment->commentDate) ];
            $count++;
        }
        $this->addTile( 
            'comments',
            __( 'Comments', 'precipace' ),
            number_format( count( $comments ) ),
            array( __( 'Name', 'precipice' ), __( 'Post', 'precipice' ), __( 'Date', 'precipice' ) ),
            $commentData,
            'none',
            'normal',
            true
        );

        global $wpdb;
        $postTypes = array_merge( [ 'post', 'page' ], get_post_types( [ '_builtin' => false ] ) );
        foreach( $postTypes as $postType ) {
            $query = $wpdb->prepare( "SELECT count(*) AS total FROM " . $wpdb->prefix . 'posts WHERE post_type=%s', $postType );
            $count = $wpdb->get_row( $query );
            $labels = get_post_type_labels( get_post_type_object( $postType ) );

            $posts = query_posts( [ 'post_type' => $postType, 'showposts' => 5, 'post_status' => 'publish' ] );
            $postsData = [];

            foreach( $posts as $post ) {
                $postsData[] = [ $post->post_title, date( 'M jS, Y', strtotime( $post->post_date ) ) ];
            }

            $this->addTile( 
                'post_type_' . $postType,
                sprintf( __( '%s', 'precipace' ), $labels->name ),
                $count->total,
                array( __( 'Name', 'precipice' ), __( 'Published', 'precipace' ) ),
                $postsData,
                'none',
                'normal',
                true
            );      
        }

        $plugins = get_plugins();
        $plugin_data = [];
        foreach( $plugins as $plugin ) {
            $plugin_data[] = array( $plugin[ 'Name' ], $plugin[ 'Version' ] );
        }
        $this->addTile( 
            'plugins',
            __( 'Plugins', 'precipace' ),
            number_format( count( $plugins ) ),
            array( __( 'Name', 'precipice' ), __( 'Version', 'precipace' ) ),
            $plugin_data
        );

        $themes = wp_get_themes();
        $theme_data = [];
        foreach( $themes as $theme ) {
            $theme_data[] = array( $theme[ 'Name' ], $theme[ 'Version' ] );
        }
        $this->addTile( 
            'themes',
            __( 'Themes', 'precipace' ),
            number_format( count( $themes ) ),
            array( __( 'Name', 'precipice' ), __( 'Version', 'precipace' ) ),
            $theme_data
        );

        $this->addTile( 
            'content',
            __( 'Content', 'precipace' ),
            number_format( 1000 ),
            array( __( 'Type', 'precipice' ), __( 'Metric', 'precipace' ) ),
            array( 
                [ 353435, 'Words' ],
                [ 355, 'Minutes' ],
                [ 355, 'Hours' ],
            )
        );


    }

    public function getSortedTiles() {
        $newTiles = [];

        ksort( $this->tiles );

        foreach( $this->tiles as $priority => $tileData ) {
            $newTiles = array_merge( $newTiles, $tileData );
        }

        return $newTiles;
    }

    public function doAdminRedirection() {
        //echo home_url() . $_SERVER[ 'REQUEST_URI' ] . ' ' . admin_url();  die;
        if (  home_url() . $_SERVER[ 'REQUEST_URI' ] == admin_url() ) {
            header( 'Location: ' . admin_url( 'index.php?page=index.php') );
            die;
        }
    }

    public function outputDashboard() {
        require_once( PRECIPICE_MAIN_DIR . '/admin/dashboard.php' );
    }

    public function removeWordPresDashboard() {
        do_action( 'precipice_setup_tiles', $this );
        
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
