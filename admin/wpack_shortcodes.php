<?php

/**
 * Name: Unsocials WPackage
 * Plugin URI: https://developer.unsocials.com
 * Version: 1.0.0
 * Author: Unsocials
 * Author URI: https://developer.unsocials.com
 * Copyright 2021 Unsocials
 * */

/**
 * L'utente non potra accedere al file php
 */
    if (!defined('ABSPATH')) exit;
    global $wpdb;

function microsoft_maps_Mappa(){
	return '<section style="position:relative;" class="wrapper_">
                <div class="overlay_">
                    <div class="cerchio"></div>
                </div>
                <div id="mappa"></div>
                <div class="slider">
                    <div class="freccia" onclick="scorri(\'L\')" ontouchstart="scorri(\'L\')">
                        <div class="arrow sx"></div>
                    </div>
                    <section class="section">
                        <div class="container" id="cont"></div>
                    </section>
                    <div class="freccia" onclick="scorri(\'R\')" ontouchstart="scorri(\'R\')">
                        <div class="arrow dx"></div>
                    </div>
                </div>
            </section>
            <script src="'. plugin_dir_url('unsocials_wpackage'). '/admin/wpack-maps/../library/js/slider.js"></script>
            <script src="'. plugin_dir_url('unsocials_wpackage'). '/admin/wpack-maps/../library/js/bingMaps.js"></script>
            <script>
                function back(){
                    carica("'. plugin_dir_url('unsocials_wpackage'). '/admin/", "Mappa", "slider");
                    document.querySelector(".overlay_").classList.add("scompari");
                    setTimeout(() => {
                        document.querySelector(".overlay_").style.display = "none";
                    }, 1500);
                    console.log("Mappa caricata correttamente");
                }
            </script>
            <script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?key=Ao4EAuMEj_NObd3zTCqDOPM2gwsqoAmBi4VCLKxH5KW1rC5Hwga_r0IHrCSufd8S&callback=back"></script>';
}

add_shortcode("Mappa", "microsoft_maps_Mappa");

function myPostData(){
	return '<span class="t-entry-date" style="color: rgb(247, 53, 69);font-size:20px;">' . get_the_date() . '</span>';
}
add_shortcode("dataPost", "myPostData");


function microsoft_maps_AreaVerde(){
    return '<section style="position:relative;" class="wrapper_">
                <div class="overlay_">
                    <div class="cerchio"></div>
                </div>
                <div id="mappa"></div>
                <div class="button">
                    <section class="section">
                        <div class="container" id="cont"></div>
                    </section>
                </div>
            </section>
            <script src="'. plugin_dir_url('unsocials_wpackage'). '/admin/wpack-maps/../library/js/bingMaps.js"></script>
            <script>
                function back(){
                    carica("'. plugin_dir_url('unsocials_wpackage'). '/admin/", "AreaVerde", "button");
                    document.querySelector(".overlay_").classList.add("scompari");
                    setTimeout(() => {
                        document.querySelector(".overlay_").style.display = "none";
                    }, 1500);
                }
            </script>
            <script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?key=Ao4EAuMEj_NObd3zTCqDOPM2gwsqoAmBi4VCLKxH5KW1rC5Hwga_r0IHrCSufd8S&callback=back"></script>';
}

add_shortcode("areaverde", "microsoft_maps_AreaVerde");