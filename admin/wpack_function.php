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


/**
 * Funzione che richiama l'header
 */
function my_plugin_header($header = 'header'){
return include(MY_PLUGIN_PATH . 'admin/' . $header . '.php');
}

/**
 * Funzione che richiama il footer
 */
function my_plugin_footer($footer = 'footer'){
return include(MY_PLUGIN_PATH . 'admin/' . $footer . '.php');
}

?>