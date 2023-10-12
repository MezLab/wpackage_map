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
$res = NULL;
$s = "";

/** $_GET che visualizza
 * la scelta dell'utente
 */

(isset($_GET["data"])) ? $data = $_GET["data"] : $data = 'default';
(isset($_GET["geo"])) ? $geoJson = $_GET["geo"] : $geoJson = NULL;

$idMappa = $_GET["idMap"];
$idMappaInt = intval($idMappa);
/** ---------------------*/

/** 
 * Funzione che riporta lo shortcode
 * grazie al campo ID
 */
$code = $wpdb->get_results("select maps_shortcode from bing_maps where ID=$idMappaInt");
/** ---------------------*/


require_once(MY_PLUGIN_PATH . 'admin/wpack_function.php');
?>

<?php my_plugin_header(); ?>

<section class="home">
    <h1 class="titlePage">Microsoft Bing Maps</h1>
    <div class="description_code">
        <p class="subTitle"><b>Copia</b> e <b>inserisci</b> lo <em>shortcode</em> nella <b>pagina/sezione</b> che preferisci per visualizzare la mappa.</p>
    </div>
    <!-- Qui dovrà essere inserito un ciclo while che mostra i shortcode creati -->
    <p class='byFlex'><i data-class='copy' class='fa fa-copy fa-2x fa-fw'><span class='shortcode'><?php echo $code[0]->maps_shortcode ?></span></i></p>
    <div class="description_code">
        <h2>Menu Opzioni</h2>
        <p class="subTitle">Scegli una delle opzioni presenti</p>
    </div>
    <div class="wpack_box bingMaps">
        <div class="box">
            <h3>Nuova <b>Postazione</b></h3>
            <a href="?page=settingMaps&idMap=<?php echo $idMappa?>&data=newPoint"?>Entra</a>
        </div>
        <div class="box">
            <h3>Elimina <b>Postazione</b></h3>
            <a href="?page=settingMaps&idMap=<?php echo $idMappa?>&data=updatePoint"?>Entra</a>
        </div>
        <div class="box">
            <h3>Aggiungi <b>Categoria</b></h3>
            <a href="?page=settingMaps&idMap=<?php echo $idMappa?>&data=newCategory"?>Entra</a>
        </div>
        <div class="box">
            <h3>Elimina <b>Categoria</b></h3>
            <a href="?page=settingMaps&idMap=<?php echo $idMappa?>&data=deleteCategory"?>Entra</a>
        </div>
    </div>

    <?php 
        switch ($data) {
            case 'newPoint':
                $res = $wpdb->get_results("select * from bing_maps_category;", ARRAY_A);
                for($i = 0; $i < count($res); $i++){
                    $s .= "<option value='" . $res[$i]["name_file"] . "'>" . $res[$i]["title"] . "</option>\n";
                }
                //echo $_SERVER['REQUEST_METHOD'];
                if($_SERVER['REQUEST_METHOD'] != 'POST' && $geoJson == NULL){
                            echo "<div class='esterno'>
                            <div class='container'>
                            <h1 align='center'>Scegli cosa inserire</h1>
                                <a class='linkGeoJson' href='?page=settingMaps&idMap=$idMappa&data=newPoint&geo=point''>Punto Cardinale</a>
                                <a class='linkGeoJson' href='?page=settingMaps&idMap=$idMappa&data=newPoint&geo=line''>Linea</a>
                                <a class='linkGeoJson' href='?page=settingMaps&idMap=$idMappa&data=newPoint&geo=polygon''>Area</a>
                            </div> 
                            </div>";
                }else{
                    switch ($geoJson) {
                        
                        case 'point':
                        if($_POST["categoria"] == NULL){
                        // "<h1>" . get_template_directory() . "'/../uncode-child/risorse/json_infomobility/ . </h1>"
                            echo "<div class='esterno'>
                                <form method='POST' action=''>
                                <a class='linkGeoJson' href='https://geojson.io/#map=13/44.7949/-349.6819' target='_blank'>Inserisci il punto</a>
                                <label style='color:#fff;padding:0;'>Scegli la categoria</label>
                                <select class='riga' name='categoria' required>
                                    <option value='' disabled selected>Categoria</option>" . $s . "
                                </select>
                                <input class='riga' name='title' type='text' value='' placeholder='Titolo' required>
                                <input class='riga' name='description' type='text' value='' placeholder='Descrizione' required>
                                <input class='riga' name='lat' type='text' value='' placeholder='Longitudine (gradi decimali)' required>
                                <input class='riga' name='long' type='text' value='' placeholder='Latitudine (gradi decimali)' required>
                                <input class='riga' type='submit' value='Aggiorna File'>
                                <div class='menu_bot'><a href='?page=settingMaps&idMap=$idMappa'>Chiudi</a><input type='reset' value='Reset'></div>
                                </form>
                                </div>";
                        }else{
                            $categoria_point = $_POST["categoria"]; // Categoria dove andra inserito il punto
                            $titolo_point = $_POST["title"]; // Titolo del punto
                            $descrizione_point = $_POST["description"]; // Descrizione del punto
                            $lat_point = $_POST["lat"]; // Latitudine del punto
                            $long_point = $_POST["long"]; // Longitudine del punto

                            $res_1 = $wpdb->get_results("select url_marker, color from bing_maps_category where name_file = '" . $categoria_point . "';", ARRAY_A);

                            $wpdb->update("bing_maps_category", array("shape" => $geoJson), array("name_file" => $categoria_point ));

                            $wpdb->insert("bing_maps_point", array("title" => $titolo_point, "description" => $descrizione_point, "coordX" => $lat_point, "coordY" => $long_point, "name_cat" => $categoria_point));

                            $AllPoint = $wpdb->get_results("select * from bing_maps_point where name_cat = '" . $categoria_point . "';", ARRAY_A);

                            $jsonPrint = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/" . $categoria_point . ".json"; // File Json richiesto per la stampa
                            $temp = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/temp.txt"; // File TXT Temporaneo per la creazione del JSON

                            /**
                             * Apertura File Json
                             */
                            $start = '{"type":"FeatureCollection","features":';
                            fwrite(fopen($jsonPrint, 'w'), $start . "\n");

                            $newArray = array(); // Nuovo Array

                            for($i = 0; $i < count($AllPoint); $i++){
                                /**
                                 * Oggetto @var $bing
                                 * con le proprietà di tipo POINT
                                 */
                                $bing = array(
                                    "type" => "Feature",
                                    "properties" => array(
                                        "marker-color" => $res_1[0]["color"],
                                        "marker-size" => "large",
                                        "icon" => plugin_dir_url( __FILE__ ). "marker/" . $res_1[0]["url_marker"],
                                        "title" =>$AllPoint[$i]["title"],
                                        "description" =>$AllPoint[$i]["description"],
                                        "visible" => false
                                    ),
                                    "geometry" => array(
                                        "type" => "Point",
                                        "coordinates" => array(
                                            floatval($AllPoint[$i]["coordX"]),
                                            floatval($AllPoint[$i]["coordY"])
                                        )
                                    )
                                );

                                /**
                                 * Apro il file temporaneo e stampo
                                 * sul file temp.txt
                                 * l'oggetto @var $bing con le coordinate
                                 * e le proprietà
                                 */
                                fwrite(fopen($temp, 'a'), json_encode($bing) . "\n");

                                /**
                                 * Inserisco le @var $bing come lista 
                                 * nell @var $newArray
                                 */
                                $newArray[$i] = json_decode(file($temp)[$i]);

                                fclose($temp);
                                
                            }
                            unlink($temp);
                            /**
                            * Stampo il file JSON
                            * grazie alla lista creata in @var $newArray 
                            */
                            fwrite(fopen($jsonPrint, 'a'), json_encode($newArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

                            /**
                             * Chiusura File Json
                             */
                            $end = '}';
                            fwrite(fopen($jsonPrint, 'a'), $end . "\n");
                            fclose($jsonPrint);
                        }
                        break;
                        case 'line' :
                        if($_POST["categoria"] == NULL){
                            echo "<div class='esterno'>
                                <form method='POST' action=''>
                                <a class='linkGeoJson' href='https://geojson.io/#map=13/44.7949/-349.6819' target='_blank'>Inserisci la linea</a>
                                <label style='color:#fff;padding:0;'>Scegli la categoria</label>
                                <select class='riga' name='categoria' required>
                                    <option value='' disabled selected>Categoria</option>" . $s . "
                                </select>
                                <input class='riga' name='title' type='text' value='' placeholder='Titolo' required>
                                <input class='riga' name='description' type='text' value='' placeholder='Descrizione' required>
                                <textarea name='coords' id='' cols='30' rows='10' placeholder='Inserisci le coordinate'></textarea>
                                <input class='riga' type='button' value='Elimina Spazi' onclick='cleanCoords();'>
                                <input class='riga' type='submit' value='Aggiorna File'>
                                <div class='menu_bot'><a href='?page=settingMaps&idMap=$idMappa'>Chiudi</a><input type='reset' value='Reset'></div>
                                </form>
                                </div>";
                            }else{
                                $categoria_line = $_POST["categoria"]; // Categoria dove andra inserito la linea
                                $titolo_line = $_POST["title"]; // Titolo della linea
                                $descrizione_line = $_POST["description"]; // Descrizione della linea
                                $coord_line = $_POST["coords"]; // Coordinate della linea


                                $res_1 = $wpdb->get_results("select color from bing_maps_category where name_file = '" . $categoria_line . "';", ARRAY_A);

                                $wpdb->update("bing_maps_category", array("shape" => $geoJson), array("name_file" => $categoria_line ));

                                $wpdb->insert("bing_maps_line", array("title" => $titolo_line, "description" => $descrizione_line, "coords" => $coord_line, "name_cat" => $categoria_line));

                                $AllPoint = $wpdb->get_results("select * from bing_maps_line where name_cat = '" . $categoria_line . "';", ARRAY_A);

                                $jsonPrint = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/" . $categoria_line . ".json"; // File Json richiesto per la stampa
                                $temp = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/temp.txt"; // File TXT Temporaneo per la creazione del JSON

                                /**
                                 * Apertura File Json
                                 */
                               $start = '{"type":"FeatureCollection","features":';
                                fwrite(fopen($jsonPrint, 'w'), $start . "\n");

                                $newArray = array(); // Nuovo Array

                                for($i = 0; $i < count($AllPoint); $i++){
                                    /**
                                     * Oggetto @var $bing
                                     * con le proprietà di tipo POINT
                                     */
                                    $bing = array(
                                        "type" => "Feature",
                                        "properties" => array(
                                            "stroke-color" => $res_1[0]["color"],
                                            "fill-color" => $res_1[0]["color"],
                                            "strokeThickness" => 3,
                                            "strokeDashArray" => "[3,2]",
                                            "title" => $AllPoint[$i]["title"],
                                            "description" => $AllPoint[$i]["description"],
                                            "visible" => false
                                        ),
                                        "geometry" => array(
                                            "type" => "LineString",
                                            "coordinates" => array(
                                                $AllPoint[$i]["coords"]
                                            )
                                        )
                                    );

                                    /**
                                 * Apro il file temporaneo e stampo
                                 * sul file temp.txt
                                 * l'oggetto @var $bing con le coordinate
                                 * e le proprietà
                                 */
                                fwrite(fopen($temp, 'a'), json_encode($bing) . "\n");

                                /**
                                 * Inserisco le @var $bing come lista 
                                 * nell @var $newArray
                                 */
                                $newArray[$i] = json_decode(file($temp)[$i]);

                                fclose($temp);
                                
                            }
                            unlink($temp);
                            /**
                            * Stampo il file JSON
                            * grazie alla lista creata in @var $newArray 
                            */
                            fwrite(fopen($jsonPrint, 'a'), str_replace(array('["', '"]'), array('[', ']'), json_encode($newArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));

                            /**
                             * Chiusura File Json
                             */
                            $end = '}';
                            fwrite(fopen($jsonPrint, 'a'), $end . "\n");
                            fclose($jsonPrint);
                            }                       
                        break;
                        case 'polygon' :
                        if($_POST["categoria"] == NULL){
                            echo "<div class='esterno'>
                                <form method='POST' action=''>
                                <a class='linkGeoJson' href='https://geojson.io/#map=13/44.7949/-349.6819' target='_blank'>Inserisci l'area</a>
                                <label style='color:#fff;padding:0;'>Scegli la categoria</label>
                                <select class='riga' name='categoria' required>
                                    <option value='' disabled selected>Categoria</option>" . $s . "
                                </select>
                                <input class='riga' name='title' type='text' value='' placeholder='Titolo' required>
                                <input class='riga' name='description' type='text' value='' placeholder='Descrizione' required>
                                <textarea name='coords' id='' cols='30' rows='10' placeholder='Inserisci le coordinate'></textarea>
                                <input class='riga' type='button' value='Elimina Spazi' onclick='cleanCoords();'>
                                <label style='color:#fff;padding:0;'>Scegli il colore dell'area</label>
                                <div class='catColor'>
                                    <input class='riga' name='colore' type='color' required>
                                    <input style='text-align:center;' class='riga' type='text' name='esadecimale' placeholder='#000000'>
                                    <p class='btn_maps'>Inserisci</p>
                                </div>
                                <input class='riga' type='submit' value='Aggiorna File'>
                                <div class='menu_bot'><a href='?page=settingMaps&idMap=$idMappa'>Chiudi</a><input type='reset' value='Reset'></div>
                                </form>
                                </div>";
                        }else{
                                $categoria_polygon = $_POST["categoria"]; // Categoria dove andra inserito l'area
                                $titolo_polygon = $_POST["title"]; // Titolo dell'area
                                $descrizione_polygon = $_POST["description"]; // Descrizione dell'area
                                $coord_polygon = $_POST["coords"]; // Coordinate dell'area
                                $color_polygon = $_POST["colore"]; // Colore dell'area

                                /**
                                 * Trasformazione HEX
                                 * in RGB
                                 */
                                list($r, $g, $b) = sscanf($color_polygon, "#%02x%02x%02x");

                                $wpdb->insert("bing_maps_polygon", array("title" => $titolo_polygon, "description" => $descrizione_polygon, "coords" => $coord_polygon, "color" => $color_polygon, "name_cat" => $categoria_polygon));

                                $wpdb->update("bing_maps_category", array("shape" => $geoJson), array("name_file" => $categoria_polygon ));

                                $AllPoint = $wpdb->get_results("select * from bing_maps_polygon where name_cat = '" . $categoria_polygon . "';", ARRAY_A);

                                $jsonPrint = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/" . $categoria_polygon . ".json"; // File Json richiesto per la stampa
                                $temp = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/temp.txt"; // File TXT Temporaneo per la creazione del JSON

                                /**
                                 * Apertura File Json
                                 */
                               $start = '{"type":"FeatureCollection","features":';
                                fwrite(fopen($jsonPrint, 'w'), $start . "\n");

                                $newArray = array(); // Nuovo Array

                                for($i = 0; $i < count($AllPoint); $i++){
                                    /**
                                     * Oggetto @var $bing
                                     * con le proprietà di tipo POINT
                                     */
                                    $bing = array(
                                        "type" => "Feature",
                                        "properties" => array(
                                            "stroke-color" => $AllPoint[$i]["color"],
                                            "strokeThickness" => 0,
                                            "fill-color" => "rgba(" . $r .", " . $g . ", " . $b . ", 0.5)",
                                            "title" => $AllPoint[$i]["title"],
                                            "description" => $AllPoint[$i]["description"],
                                            "visible" => false
                                        ),
                                        "geometry" => array(
                                            "type" => "Polygon",
                                            "coordinates" => array(
                                                array($AllPoint[$i]["coords"])
                                            )
                                        )
                                    );

                                    /**
                                 * Apro il file temporaneo e stampo
                                 * sul file temp.txt
                                 * l'oggetto @var $bing con le coordinate
                                 * e le proprietà
                                 */
                                fwrite(fopen($temp, 'a'), json_encode($bing) . "\n");

                                /**
                                 * Inserisco le @var $bing come lista 
                                 * nell @var $newArray
                                 */
                                $newArray[$i] = json_decode(file($temp)[$i]);

                                fclose($temp);
                                
                            }
                            unlink($temp);
                            /**
                            * Stampo il file JSON
                            * grazie alla lista creata in @var $newArray 
                            */
                            fwrite(fopen($jsonPrint, 'a'), str_replace(array('["', '"]'), array('[', ']'), json_encode($newArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));

                            /**
                             * Chiusura File Json
                             */
                            $end = '}';
                            fwrite(fopen($jsonPrint, 'a'), $end . "\n");
                            fclose($jsonPrint);
                        }
                        break;
                        default:
                        # code...
                        break;
                    }
                }                   
            break;
            case 'newCategory':
                if($_SERVER['REQUEST_METHOD'] != 'POST'){
                    echo    "<div class='esterno'>
                        <form action='' method='POST' enctype='multipart/form-data'>
                        <h1 align='center'>Crea una nuova categoria</h1>
                        <input type='text' class='riga' name='categoria' placeholder='Categoria' required>
                        <label style='color:#fff;padding:0;'>Inserisci l'icona</label>
                        <input id='file-upload' class='riga' name='icona' type='file' accept='image/png' required>
                        <label style='color:#fff;padding:0;'>Inserisci Marker</label>
                        <input id='marker-upload' class='riga' name='marker' type='file' accept='image/png' required>
                        <label style='color:#fff;padding:0;'>Scegli il colore</label>
                        <div class='catColor'>
                            <input class='riga' name='colore' type='color' required>
                            <input style='text-align:center;' class='riga' type='text' name='esadecimale' placeholder='#000000'>
                            <p class='btn_maps'>Inserisci</p>
                        </div>
                        <input type='submit' class='riga' value='Crea file'>
                        <div class='menu_bot'><a href='?page=settingMaps&idMap=" . $idMappa . "'>Chiudi</a><input type='reset' value='Reset'></div>
                        </div>";
                }else{
                    $y = false;
                    $ctrlCat = $wpdb->get_results("select title from bing_maps_category;", ARRAY_A);
                    $mapSlt =  $wpdb->get_results("select maps_name from bing_maps where ID=$idMappaInt ;", ARRAY_A);
                    if(empty($ctrlCat)){
                        $y = true;
                    }else{
                        for($i = 0; $i < count($ctrlCat); $i++){
                            if($ctrlCat[$i]["title"] == $_POST["categoria"]){
                                echo "<div class='bad'><h1 align='center'>Questa categoria è già presente</h1></div>";
                                $y = false;
                            }else{
                                $y = true;
                            }
                        }

                    }
                    if($y){

                        $name_title = $_POST['categoria'];
                        $name_title = str_replace(' ', '', $name_title);
                        $name_file = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/" . $name_title . ".json";

                        if(file_exists($name_file)){
                                    echo "<div class='bad'><h1 align='center'>Esiste gia' un file di questa categoria</h1></div>";
                        }else{
                                /**
                             * Crea il file JSON nella 
                             * cartella GEOJSON
                             */
                            touch($name_file);
                            echo "<div class='good'><h1 align='center'>Il file e' stato creato</h1></div>";

                            function createSymbol($name, $directory){

                                //percorso della cartella dove mettere i file caricati dagli utenti
                                $directory = MY_PLUGIN_PATH . 'admin/wpack-maps/'. $directory .'/';
                                //Recupero il percorso temporaneo del file
                                $iconaTemp = $_FILES[$name]['tmp_name'];
                                //recupero il nome originale del file caricato
                                $icona_name = $_FILES[$name]['name'];
                                //copio il file dalla sua posizione temporanea alla mia cartella upload
                                if(file_exists($directory . $icona_name)){
                                    echo "<div class='bad'><h1 align='center'>Esiste gia' questa icona</h1></div>";
                                }else{
                                    if(move_uploaded_file($iconaTemp, $directory . $icona_name)){
                                        echo "<div class='good'><h1 align='center'>Icona inserita correttamente</h1></div>";
                                    }else{
                                        echo "<div class='bad'><h1 align='center'>OPS!! icona non inserita</h1></div>";
                                    }
                                }
                            }

                            createSymbol('icona', 'icon');
                            createSymbol('marker', 'marker');
                            
                        }

                        /**
                         * Inserimento nel Database della categoria
                         */
                        $risultato = $wpdb->insert("bing_maps_category", array("title" => $_POST["categoria"], "name_file" => $name_title, "url_icon" => $_FILES['icona']['name'], "url_marker" => $_FILES['marker']['name'], "color" => $_POST["esadecimale"], "id_map" => $idMappa));
                        echo "<div class='good'><h1 align='center'>Categoria inserita</h1></div>";

                        /**
                         * Inserimento nel file JSON
                         * della ID Mappato 
                         * nella directory Risorse 
                         */
                        $catSlt =  $wpdb->get_results("select * from bing_maps_category where title = '" . $_POST["categoria"] . "';", ARRAY_A);

                        $json_Map = MY_PLUGIN_PATH . "admin/wpack-maps/risorse/" . $mapSlt[0]['maps_name'] . ".json" ;
                        $jsonTemp = MY_PLUGIN_PATH . "admin/wpack-maps/risorse/temp_" . $mapSlt[0]['maps_name'] . ".txt" ;

                        $newArray_1 = array(); // Nuovo Array

                        $Obj = array(
                            "NomeFile"=> $catSlt[0]['name_file'] . ".json",
                            "Titolo"=> $catSlt[0]['title'],
                            "Icona" => $catSlt[0]['url_icon'],
                            "Marker" => $catSlt[0]['url_marker'],
                            "Color" =>  $catSlt[0]['color'],
                        );
                        
                        /**
                        * Apro il file temporaneo e stampo
                        * sul file temp.txt
                        * l'oggetto @var $bing con le coordinate
                        * e le proprietà
                        */
                        fwrite(fopen($jsonTemp, 'a'), json_encode($Obj) . "\n");

                        /**
                        * Inserisco le @var $bing come lista 
                        * nell @var $newArray
                        */
                        for($i = 0; $i < count(file($jsonTemp)); $i++){
                            $newArray_1[$i] = json_decode(file($jsonTemp)[$i]);
                        }
                            
                        fclose($jsonTemp);
                        fwrite(fopen($json_Map, 'w'), json_encode($newArray_1, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));


                    }else{
                        echo "<div class='bad'><h1 align='center'>Categoria non inserita</h1></div>";
                    }
                    
                }
            break;
            case "deleteCategory":
                if($_SERVER["REQUEST_METHOD"] != "POST"){
                    $s = "";
                    echo    "<div class='esterno'>
                            <form action='' method='post'>
                            <h1 align='center'>Scegli il file da eliminare</h1>
                            <select class='riga' name='categoria' required>
                            <option value=''>Categoria</option>";

                    $res = $wpdb->get_results("select * from bing_maps_category;", ARRAY_A);
                    for($i = 0; $i < count($res); $i++){
                        $s .= "<option value='" . $res[$i]["name_file"] . "'>" . $res[$i]["title"] . "</option>\n";
                    }

                    echo $s . "</select>
                    <input class='riga' type='submit' value='Elimina file'>
                    <div class='menu_bot'><a href='?page=settingMaps&idMap=$idMappa'>Chiudi</a><input type='reset' value='Reset'>
                    </form>";
                }else{
                    $categoria = $_POST["categoria"];                         
                    $res = $wpdb->get_results("select name_file, url_icon, url_marker from bing_maps_category where name_file = '" . $categoria . "';", ARRAY_A);

                    $name_json = MY_PLUGIN_PATH . "admin/wpack-maps/geojson/" . $res[0]["name_file"] . ".json";
                    $name_img = MY_PLUGIN_PATH . "admin/wpack-maps/icon/" . $res[0]["url_icon"];
                    $name_marker = MY_PLUGIN_PATH . "admin/wpack-maps/marker/" . $res[0]["url_marker"];
                    /**
                     * Elimina i file dal server
                     */
                    unlink($name_json);
                    unlink($name_img);
                    unlink($name_marker);

                    // elimina il file dal database e dal filesystem
                    if($wpdb->delete("bing_maps_category", array("name_file" => $categoria))){
                        echo "<div class='good'><h1 align='center'>Il file e' stato eliminato</h1></div>";

                    }else{
                        echo "<div class='bad'><h1 align='center'>Si e' verificato un errore durante l'eliminazione del file.</h1></div>";
                    }
                }
            break;
            case "updatePoint":
            $s = "";
            if($_SERVER["REQUEST_METHOD"] != "POST"){

                $res = $wpdb->get_results("select * from bing_maps_category;", ARRAY_A);
                for($i = 0; $i < count($res); $i++){
                    $s .= "<option value='" . $res[$i]["name_file"] . "'>" . $res[$i]["title"] . "</option>\n";
                }

                echo    "<div class='esterno'>
                        <form action='' method='POST' enctype='multipart/form-data'>
                        <h1 align='center'>Modifica un file</h1>
                        <select class='riga' name='categoria' required>
                        <option value='' disabled selected>Categoria</option>" . $s . "
                        </select>
                        <input type='submit' class='riga' value='Vedi risultati'>
                        <div class='menu_bot'><a href='?page=settingMaps&idMap=$idMappa'>Chiudi</a><input type='reset' value='Reset'>
                        </form>
                        </div>";
            }else{
                // echo file_get_contents("php://input");
                $categoria = $_POST["categoria"];
                $shape = $wpdb->get_results("select shape from bing_maps_category where name_file = '" . $categoria . "';", ARRAY_A);
                $res = $wpdb->get_results("select * from bing_maps_" . $shape[0]["shape"] . " where name_cat = '" . $categoria . "';", ARRAY_A);
                $intestazione = ["", "Titolo", "Descrizione"];
                $heading = "";
                $x = "";

                echo "<div class='esterno'>
                        <div class='tabellaFile'>
                            <div class='app'>
                            <h1 align='center'>Tabella Categoria [ " . $categoria . " ]</h1>
                                <table class='table'>
                                    <tr>";
                                        for($i = 0; $i < count($intestazione); $i++){
                                            $heading .= "<th>" . $intestazione[$i] . "</th>";
                                        }
                                    echo $heading . "
                                    </tr>";
                                        for($i = 0; $i < count($res); $i++){
                                            $x .= "<tr><td><input type='checkbox' data-select='" . $i . "' onclick='aggiornaBottone()'></td>" . "<td>" . $res[$i]["title"] . "</td><td>" . $res[$i]["description"] . "</td><tr>";
                                        }
                                        echo $x . "
                                </table>
                                <div class='menu_bot'><a href='?page=settingMaps&idMap=$idMappa'>Chiudi</a><a href='?page=settingMaps&idMap=$idMappa&data=deleteShape'>Elimina</a></div>
                            </div>
                        </div>
                    </div>";
            }
                break;
                default:
                break;
        }
?>