<?php
 header("Content-Type: application/json");
 // php module for saving json card data

    // first load the latest version
    $url = 'linki-data.json';
    $data = file_get_contents($url);
    $linkiData = json_decode($data); 

    // remove slashes
    //$input = stripslashes($_GET["grid"]);
    $input = stripslashes(file_get_contents("php://input"));
    
    if (!empty($input)) {
        $cards = json_decode($input);

        $linkiData->version++;
        $linkiData->cards = $cards;
        
        $export = stripslashes(json_encode($linkiData));

        $fp = fopen('results.json', 'w');
        fwrite($fp, $export);
        fclose($fp);
    }

    echo "ok";
?>