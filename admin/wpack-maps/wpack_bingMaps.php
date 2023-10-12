<?php

/**
 * Name: Unsocials WPackage
 * Plugin URI: https://developer.unsocials.com
 * Version: 1.0.1
 * Author: Unsocials
 * Author URI: https://developer.unsocials.com
 * Copyright 2021 Unsocials
 * */

/**
 * L'utente non potra accedere al file php
 */
// if (!defined('ABSPATH')) exit;
global $wpdb;
$res = NULL;
$s = "";

/** $_GET che visualizza
 * la scelta dell'utente
 */
if(isset($_GET["data"])){
    $data = $_GET["data"];
}
/** ---------------------*/

require_once(MY_PLUGIN_PATH . 'admin/wpack_function.php');
?>

<?php my_plugin_header(); ?>

<section class="home">
    <h1 class="titlePage">Microsoft Bing Maps</h1>
    <?php 
    /**
     * Controllo che le tabelle 
     * sul database siano state 
     * correttamente installate
     */
    ?>

    <?php
    $fileDB = MY_PLUGIN_PATH . 'admin/wpack-maps/db.txt';

    if(!isset($_GET["db"])){
        $_GET["db"] = 'default';
    }
    
    switch ($_GET["db"]){
        // Crea Tabella
        case 'create':
                /**
         * Creazione Database
         */
        
        // creazione della tabella per il login
        $wpdb->query('CREATE TABLE bing_maps
        (ID bigint(20) NOT NULL AUTO_INCREMENT,
        maps_name varchar(60) NOT NULL,
        maps_shortcode varchar(255) NOT NULL,
        PRIMARY KEY (ID))');

        $wpdb->query('CREATE TABLE bing_maps_category
        (title varchar(60) NOT NULL,
        name_file varchar(255) NOT NULL,
        url_icon varchar(255) NOT NULL,
        url_marker varchar(255) NOT NULL,
        color varchar(60) NOT NULL,
        shape varchar(60) NULL,
        id_map bigint(20) NOT NULL)');

        $wpdb->query('CREATE TABLE bing_maps_point
        (ID bigint(20) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        description varchar(255) NOT NULL,
        coordX varchar(255) NOT NULL,
        coordY varchar(255) NOT NULL,
        name_cat varchar(255) NOT NULL,
        PRIMARY KEY (ID))');

        $wpdb->query('CREATE TABLE bing_maps_line
        (ID bigint(20) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        description varchar(255) NOT NULL,
        coords mediumtext NOT NULL,
        name_cat varchar(60) NOT NULL,
        PRIMARY KEY (ID))');

        $wpdb->query('CREATE TABLE bing_maps_polygon
        (ID bigint(20) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        description varchar(255) NOT NULL,
        coords mediumtext NOT NULL,
        color varchar(60) NOT NULL,
        name_cat varchar(60) NOT NULL,
        PRIMARY KEY (ID))');

        fwrite(fopen($fileDB, 'w'), '1');
        fclose($fileDB);
        break;
         // Aggiungi Mappa
        case 'addMap':
        $wpdb->insert("bing_maps", array("maps_name" => $_POST['nameMap'], "maps_shortcode" => "[" . $_POST['nameMap'] ."]"));
        // Stampa funzione shortcode nel file medesimo

        $apiBingMaps = "Ao4EAuMEj_NObd3zTCqDOPM2gwsqoAmBi4VCLKxH5KW1rC5Hwga_r0IHrCSufd8S";

        $file_shortcode = MY_PLUGIN_PATH . "admin/wpack_shortcodes.php";
        if(file_exists($file_shortcode)){
            $newString = $_POST['nameMap'];
            $newString = str_replace(' ', '', $newString);
            $function = PHP_EOL .'function microsoft_maps_' . $newString . '(){
	                        return <section style="position:relative;" class="wrapper_">
                            <div class="overlay_">
                                <div class="cerchio"></div>
                            </div>
                            <div id="mappa"></div>
                            <div class="esterno">
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
                        <script src="' . plugin_dir_url( __FILE__ ). '../library/js/slider.js"></script>
                        <script src="' . plugin_dir_url( __FILE__ ). '../library/js/bingMaps.js"></script>
                        <script>
                            function back(){
                                carica("' . plugin_dir_url( __DIR__ ) . '", "' . $_POST['nameMap'] . '");
                                document.querySelector(".overlay_").classList.add("scompari");
                                setTimeout(() => {
                                    document.querySelector(".overlay_").style.display = "none";
                                }, 1500);
                                console.log("Mappa caricata correttamente");
                            }
                        </script>
                        <script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?key=' . $apiBingMaps . '&callback=back"></script>\";
                        }

                        add_shortcode("' . $_POST['nameMap'] . '", "microsoft_maps_' . $newString . '");';
            fwrite(fopen($file_shortcode, "a"), $function);
            fclose($file_shortcode);
            
            /**
             * Crea il file in risorse
             * per la creazione delle categorie
             * all'interno della Mappa selezionata
             */

            $risorse = MY_PLUGIN_PATH . "admin/wpack-maps/risorse/" . $_POST['nameMap'] . ".json" ;
            touch($risorse);

        }else{
            echo "Non trovato";
        }
        break;
        default:
        break;
    }
    ?>

    <?php if(!file_exists($fileDB)){ ?>

    <div class="description_code">
        <p class="subTitle"><b>Aggiungi</b> le <b>tabelle</b> al database.</p>
        <a href="?page=wpackage_maps&db=create" class="btn">Aggiungi</a>
    </div>

    <?php }else{ ?>

    <div class="description_code">
        <p class="subTitle">Benvenuto nella creazione della tua mappa personalizzata.</p>
        <p class="subTitle"><b>Copia</b> e <b>inserisci</b> lo <em>shortcode</em> nella <b>pagina/sezione</b> che preferisci per visualizzare la mappa.</p>
        <button class="btn" onclick="openBox('addMap');">Aggiungi Mappa</button>
        <div id="addMap" class="esterno">
            <form action="?page=wpackage_maps&db=addMap" method='POST'>
                <h1 align="center">Inserisci il nome della Mappa</h1>
                <input class='riga' type="text" name="nameMap" required>
                <input class='riga' type="submit" value="Aggiungi">
                <div class='menu_bot'><a href='?page=wpackage_maps'>Chiudi</a><input type='reset' value='Reset'></div>
            </form>
        </div>
    </div>
    
    <?php 
    /** Qui dovrà essere inserito
     *  un ciclo while che mostra
     *  i shortcode creati */
        $risultato = "";
        $res = $wpdb->get_results("select * from bing_maps;", ARRAY_A);
            for($i = 0; $i < count($res); $i++){
             $risultato .= "<tr><td><p class='settingSingleMaps'><a href='?page=settingMaps&idMap=" . $res[$i]["ID"] . "'><i class='fa fa-pencil3 fa-2x fa-fw'></i></a></p></td><td><p class='nameMaps'>" . $res[$i]["maps_name"] . "</p></td><td><p class='byFlex'><i data-class='copy' class='fa fa-copy fa-2x fa-fw'><span class='shortcode'>" . $res[$i]["maps_shortcode"] . "</span></i></p></td><tr>";
        }
        
    ?>

        <table class="shortBox">
        <tr>
            <th></th>
            <th>Nome</th>
            <th>Shortcode</th>
        </tr>
        <?php echo $risultato; ?>
    </table>


    <?php } ?>
</section>

<?php my_plugin_footer(); ?>