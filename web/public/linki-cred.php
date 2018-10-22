<?php
 header("Content-Type: application/json");
 // php module for verifying credentials from json file

    // first load the latest version
    $url = 'linki-data.json';
    $data = file_get_contents($url);
    $linkiData = json_decode($data); 
    // password SHA256/HEX generator: https://hash.online-convert.com/sha256-generator
    $salt = "&*(lkjdlk&^+%";

    $input = stripslashes(file_get_contents("php://input"));

    if (!empty($input)) {
        $cred = json_decode($input);

        $pass = hash("sha256", $cred->pass . $salt);
        if ( $cred->login==$linkiData->user[0]->name and $pass==$linkiData->user[0]->id ) {
            // set cookie -> this get's checked in linki-admin.php, change accordingly.
            setcookie('Linki289867438','Linkiadmin8437912626', time()+86400*1);
            echo "ok";
        } else {
            echo "!ok";
        }

    } else {
        echo "!ok";
    }
?>