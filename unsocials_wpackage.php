<?php

/**
 * Plugin Name: Unsocials WPackage
 * Plugin URI: https://developer.unsocials.com
 * Description: Unsocials mette a disposizione un team di sviluppatori di Plugins Wordpress per semplificare le dinamiche del tuo sito web.
 * Version: 1.0.0
 * Requires PHP: 7.0
 * Author: Unsocials
 * Author URI: https://developer.unsocials.com
 * Domain Path: /language
 * Copyright 2021 Unsocials
 * */

define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * L'utente non potra accedere al file php
 */
if(!defined('ABSPATH')) exit;


class WPackage{

    // private $arguments = array(
    //     'parameter1' => '1',
    //     'parameter2' => '2'
    // );

    // private $options = array();

    function __construct(){
        add_action( 'admin_menu', array( $this , 'wpackage_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this , 'wpackage_all_styles'), 20 );

        // $this->options = get_option('wpackage', $this->arguments);
    }

    public function wpackage_menu()
    {
        add_menu_page(__('Unsocials WPackage', 'unsocials_wpackage'), __('WPackage Unsocials', 'unsocials_wpackage'), 'manage_options', 'unwpackage', array($this, 'wpackage'), plugin_dir_url(__FILE__) . 'admin/media/img/cube_min.png', 30);

        add_submenu_page('unwpackage', __('Microsoft Bing Maps', 'unsocials_wpackage'), __('Microsoft Bing Maps', 'unsocials_wpackage'), 'manage_options', 'wpackage_maps', array($this, 'wpackage_maps'));

        add_submenu_page('wpackage_maps', __('Impostazioni Mappe', 'unsocials_wpackage'), __('Impostazioni Mappe', 'unsocials_wpackage'), 'manage_options', 'settingMaps', array($this, 'wpackage_settingMaps'));

        add_submenu_page('wpackage_maps', __('Mappa Visibile', 'unsocials_wpackage'), __('Mappa Visibile', 'unsocials_wpackage'), 'manage_options', 'visibleMaps', array($this, 'wpackage_visibleMaps'));
    }

    public function wpackage(){
        require_once(MY_PLUGIN_PATH . 'admin/wpack_admin.php');
    }

    public function wpackage_shortcode(){
        require_once(MY_PLUGIN_PATH . 'admin/wpack_shortcodes.php');
    }

    // Bing Maps Microsoft
    public function wpackage_maps(){
        require_once(MY_PLUGIN_PATH . 'admin/wpack-maps/wpack_bingMaps.php');
    }
    public function wpackage_settingMaps(){
        require_once(MY_PLUGIN_PATH . 'admin/wpack-maps/wpack_settingMaps.php');
    }
    public function wpackage_visibleMaps(){
        require_once(MY_PLUGIN_PATH . 'admin/wpack-maps/wpack_visibleMaps.php');
    }

    public function wpackage_all_styles(){
        wp_enqueue_style('unsocials_wpackage_css', plugins_url('admin/library/css/unsocials_wpackage.css', __FILE__) );
        wp_enqueue_script( 'unsocials_wpackage_js', plugins_url('admin/library/js/unsocials_wpackage.js', __FILE__)  );
    }
}
$wpck = new WPackage();
$wpck->wpackage_shortcode();


?>