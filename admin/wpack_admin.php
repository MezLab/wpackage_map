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

require_once(MY_PLUGIN_PATH . 'admin/wpack_function.php');

?>

<?php my_plugin_header(); ?>

<section class="home">
    <h1 class="titlePage">Ciao <b><?php echo wp_get_current_user()->user_nicename; ?></b>,</h1>
    <h3 class="subTitle">Questi sono i <b>servizi</b> che ti <b>mettiamo a disposizione</b>.</h3>
</section>
<div class="wpack_box">
    <div class="box">
        <h3><b>Bing Map</b> Microsoft</h3>
        <a href="?page=wpackage_maps"?>Entra</a>
    </div>
</div>

<?php my_plugin_footer(); ?>