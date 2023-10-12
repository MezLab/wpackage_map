<?php
/**
 * Name: Unsocials WPackage
 * Plugin URI: https://developer.unsocials.com
 * Version: 1.0.0
 * Author: Unsocials
 * Author URI: https://developer.unsocials.com
 * Copyright 2021 Unsocials
 * */


$f = $_GET["file"];
$q = $_GET["query"];

if($f == $q){    
    setVisible($f, true);
}else{
    setVisible($f, false);
}

function setVisible($a, $b){
    // File Json richiesto per la stampa
    $jsonPrint = "geojson/" . $a;

    $c = file_get_contents($jsonPrint);
    $readFile = json_decode($c, true);


    for ($i=0; $i < count($readFile['features']); $i++) { 
        $readFile['features'][$i]['properties']["visible"] = $b;
    }
    fwrite(fopen($jsonPrint, 'w'), json_encode($readFile, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

}



?>