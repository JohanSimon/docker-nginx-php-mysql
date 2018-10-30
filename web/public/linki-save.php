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

        // making a cached version of the linki page
        $cached_cards = "";
        foreach ($linkiData->cards as $card) {
            $cached_cards .= '
            <div class="item h20 w20 '.$card->data_color.' muuri-item muuri-item-shown" data-id="'.$card->data_id.'" data-color="'.$card->data_color.'" data-title="'.$card->data_title.'" data-icon="'.$card->icon.'" data-link="'.$card->link_url.'" style="left: 0px; top: 0px; transform: translateX(0px) translateY(0px); display: block; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <a href="'.$card->link_url.'" target="_blank">
            <div class="item-content" style="opacity: 1; transform: scale(1);">
                <div class="card">
                    <div class="card-id">'.$card->data_id.'</div>
                    <div class="card-icon">
                        <i class="material-icons">'.$card->icon.'</i>
                    </div>
                    <div class="card-title">'.$card->data_title.'</div>
                </div>
            </div>
            </a>
            </div>  
            ';
        }
        // create a new index.html file from template (which is cached)
        $template_data = "<!-- Cached version, created on: ".date("Y-m-d h:i")." -->\n";
        $file_template = fopen('linkidata/offline-linki-template.html','r');

        while (!feof($file_template)) {
            $line = fgets($file_template);
            if ( (preg_replace('/\s+/', '', $line)) == "<placeholder></placeholder>" ) {
                $template_data .= $cached_cards;
            } elseif ( (preg_replace('/\s+/', '', $line)) == "<linki-title></linki-title>" ) {
                $template_data .= $linkiData->title;
            } else {
                $template_data .= $line;
            }
        }
        fclose($file_template);

        $fp = fopen('index.html', 'w');
        fwrite($fp, $template_data);
        fclose($fp);

        // change version serviceworker to refresh cache (with index.html in it)
        $version = 'const version = "' . date("Y-m-d_h:i:s_") . '"' . "\n";
        $serviceWorkerData = file_get_contents('linkidata/serviceworker-template.js');
        $serviceWorkerData = $version . $serviceWorkerData;
        file_put_contents('serviceworker.js', $serviceWorkerData);

    }

    echo "ok";
?>