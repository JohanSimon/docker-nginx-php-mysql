<?php
 header("Content-Type: application/json");
 // php module for saving json card data

    // first load the latest version
    $url = 'linki-data.json';
    $data = file_get_contents($url);
    $linkiData = json_decode($data); 

    if (!empty($linkiData->json_file)) {
        $destiny = $linkiData->json_file;
    } else {
        $destiny = "noname.json";
    }

    $input = stripslashes(file_get_contents("php://input"));

    if (!empty($input)) {
        $cards = json_decode($input);

        $linkiData->version++;
        $linkiData->cards = $cards;
        
        $export = stripslashes(json_encode($linkiData));

        $fp = fopen($destiny, 'w');
        fwrite($fp, $export);
        fclose($fp);
    }

    echo "ok";
?>