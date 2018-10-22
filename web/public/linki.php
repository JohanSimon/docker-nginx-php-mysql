<?php

// =====================================================================================================================
//    Linki v1 END USER

//  Simple link list
//  Load settings and data from JSON file. See: linki-data-template.json
// =====================================================================================================================

    // load data from json file
    $url = 'linki-data.json';
    $data = file_get_contents($url);
    $linkiData = json_decode($data); 
    $logo = $linkiData->logo;
    $title = $linkiData->title;
    $version = $linkiData->version;
?>


<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Linki - Link list</title>
    <meta name="description" content="Linki is a linklist with draggable cards">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="author" content="Johan Antonissen">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Sans:400,300,300italic,400italic,500,500italic,700,700italic">
    <link rel="stylesheet" href="styles/main.css?v=5">
    <link rel="stylesheet" href="styles/demo-grid.css?v=5">
  </head>
  <body>

    <header>
    </header>

    <section class="grid-demo">

      <div class="section-title">
        <span><?=$title?></span>
        <img height="100" src="<?=$logo?>" alt="page-logo">      
      </div>


      <div class="controls cf">

        <div class="control search">
          <div class="control-icon">
            <i class="material-icons">&#xE8B6;</i>
          </div>
          <form method="get" action="https://www.google.com/search" target="_blank">
            <input class="control-field form-control " type="text" name="q" placeholder="Search google..." />
          </form>
        </div>

        <div class="control search">
          <div class="control-icon">
            <i class="material-icons">&#xE8B6;</i>
          </div>
          <input class="control-field search-field form-control " type="text" name="search" placeholder="Search cards..." />
        </div>
        <div class="control filter">
          <div class="control-icon">
            <i class="material-icons">&#xE152;</i>
          </div>
          <div class="select-arrow">
            <i class="material-icons">&#xE313;</i>
          </div>
          <select class="control-field filter-field form-control">
            <option value="" selected>All</option>
            <option value="red">Red</option>
            <option value="blue">Blue</option>
            <option value="green">Green</option>
          </select>
        </div>

        <div class="control sort" style="display: none;">
          <div class="control-icon">
            <i class="material-icons">&#xE164;</i>
          </div>
          <div class="select-arrow">
            <i class="material-icons">&#xE313;</i>
          </div>
          <select class="control-field sort-field form-control" disabled>
            <option value="order" selected>Drag</option>
            <option value="title">Title (drag disabled)</option>
            <option value="color">Color (drag disabled)</option>
          </select>
        </div>

        <div class="control layout">
          <div class="control-icon">
            <i class="material-icons">&#xE871;</i>
          </div>
          <div class="select-arrow">
            <i class="material-icons">&#xE313;</i>
          </div>
          <select class="control-field layout-field form-control">
            <option value="left-top" selected>Left Top</option>
            <option value="left-top-fillgaps">Left Top (fill gaps)</option>
            <option value="right-top">Right Top</option>
            <option value="right-top-fillgaps">Right Top (fill gaps)</option>
            <option value="left-bottom">Left Bottom</option>
            <option value="left-bottom-fillgaps">Left Bottom (fill gaps)</option>
            <option value="right-bottom">Right Bottom</option>
            <option value="right-bottom-fillgaps">Right Bottom (fill gaps)</option>
          </select>
        </div>
      
      </div>
      </div>


        <div class="grid muuri">
<?        
        // load cards
        foreach ($linkiData->cards as $card) {
?>
        <div class="item h20 w20 <?=$card->data_color?> muuri-item muuri-item-shown" data-id="<?=$card->data_id?>" data-color="<?=$card->data_color?>" data-title="<?=$card->data_title?>" data-icon="<?=$card->icon?>" data-link="<?=$card->link_url?>" style="left: 0px; top: 0px; transform: translateX(0px) translateY(0px); display: block; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <a href="<?=$card->link_url?>" target="_blank">
                <div class="item-content" style="opacity: 1; transform: scale(1);">
                    <div class="card">
                        <div class="card-id"><?=$card->data_id?></div>
                        <div class="card-icon">
                            <i class="material-icons"><?=$card->icon?></i>
                        </div>
                        <div class="card-title"><?=$card->data_title?></div>
                    </div>
                </div>
                </a>
        </div>  
<?          
        }
?>

    </div>

   
    <div class="grid-footer">
    </div>

    </section>
    
    <footer>
      Making linkpages fun! <i class="material-icons">favorite</i> Powered by <a href="https://www.danton.nl" target="_blank">Danton</a>
    </footer>

    <script>
      // init script for javasettings
      var admin = false;
    </script>
    <script src="scripts/vendor/web-animations-2.3.1.min.js"></script>
    <script src="scripts/vendor/hammer-2.0.8.min.js"></script>
    <script src="scripts/vendor/muuri-0.7.0.js"></script>
    <script src="scripts/link-list.js?v=1"></script>

  </body>

</html>
