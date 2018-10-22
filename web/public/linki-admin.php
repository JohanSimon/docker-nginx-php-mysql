<?php

// =====================================================================================================================
//    Linki v1 ADMIN PAGE

//  Simple link list
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
          <input class="control-field search-field form-control " type="text" name="search" placeholder="Search..." />
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
        <div class="control sort">
          <div class="control-icon">
            <i class="material-icons">&#xE164;</i>
          </div>
          <div class="select-arrow">
            <i class="material-icons">&#xE313;</i>
          </div>
          <select class="control-field sort-field form-control">
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

<?
    // some basic security: check for cookie
    if (!isset($_COOKIE['Linki289867438'])) {
      // if no cookie set ask for credentials (verify by ajax)
?>
                    <div id="myLogin" class="modal">
                    <div class="modal-content">
                       <h2>Admin login<h2>

                      <div class="row">

                        <div class="row group">
                            <input id="sel-login" value="">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Login</label>
                      </div>

                        <div class="row group">
                            <input id="sel-pass" type="password" value="">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Password</label>
                      </div>

                    </div>

                    <div class="row edit-footer">
                        <div class="row" style="margin-bottom: 10px;">
                          <button id="loginBtn" class="btn btn-primary popup" onclick="verifyCredentials()"><i class="material-icons">face</i> Login<span class="popuptext" id="myPopupLogin">Login failed!</span></button>
                        </div>
                    </div>

                    </div>
                    </div>
<script>

function verifyCredentials() {    
  var cred = {};  
  cred['login'] = document.getElementById('sel-login').value;
  cred['pass'] = document.getElementById('sel-pass').value;
  
  // send to PHP helper function
  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "linki-cred.php", true);
  xhttp.setRequestHeader("Content-Type", "application/json");    
  xhttp.onreadystatechange = function () {
    if (xhttp.readyState === 4 && xhttp.status === 200) {
        if (xhttp.responseText=='ok') {
          // cookie = set by PHP-helper. Refresh the page.
          location.reload(); 
        } else {
          var popup = document.getElementById("myPopupLogin");
          popup.classList.add("show");
          setTimeout(function(){
          popup.classList.remove("show");
          }, 2500);
        }
    }
  }
  xhttp.send(JSON.stringify(cred));
}

</script>

<?
    }
?>

                    <div id="myModal" class="modal hideEdit">

                    <!-- Modal content -->
                    <div class="modal-content">
                      <h1>Edit card</h1>

                      <div class="row" style="height: 200px;">
                        <div id="placeholder" class="item green">
                                <a href="#" target="_blank" id="ph-link">
                                <div class="item-content">
                                    <div class="card">
                                        <div class="card-id" id="ph-dataid">0</div>
                                        <div class="card-icon" id="ph-icon">
                                            <i class="material-icons">cancel</i>
                                        </div>
                                        <div class="card-title" id="ph-title">Title</div>
                                    </div>
                                </div>
                                </a>
                        </div>  
                      </div>

                    <div class="row">
                        <div class="row" style="margin-bottom: 15px">
                          <div class="select">
                            <select class="select-text" name="sel-color" id="sel-color">
                              <option value="" disabled>Choose your option</option>
                              <option value="red">red</option>
                              <option value="green">green</option>
                              <option value="blue">blue</option>
                            </select>
                            <span class="select-highlight"></span>
                            <span class="select-bar"></span>
                            <label class="select-label">Color</label>
                          </div>
                        </div>

                        <div class="row" style="font-size: 12px; margin-left: 110px;"><a href="https://material.io/tools/icons/?style=baseline" target="_blank">Icon cheatsheet</a></div>
                        <div class="row" style="margin-bottom: 20px">
                          <div class="select">
                            <select class="select-text" name="sel-icon" id="sel-icon">
                              <option value="" disabled>Choose your option</option>
                              <!-- gets filled by javascript-->
                            </select>
                            <span class="select-highlight"></span>
                            <span class="select-bar"></span>
                            <label class="select-label">Icon</label>
                          </div>
                        </div>

                        <div class="row group">
                            <input id="sel-dataid" value="" style="display:none">
                            <input id="sel-title" value="Title">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Title</label>
                      </div>

                        <div class="row group">
                            <input id="sel-url" value="http://#">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>URL</label>
                      </div>

                    </div>

                    <div class="row edit-footer">
                        <div class="row" style="margin-bottom: 10px;">
                          <button id="saveBtn" class="btn btn-primary"><i class="material-icons">save</i> Save card</button>       
                        </div>
                        <div class="row">
                          <button id="cancelBtn" class="btn btn-primary"><i class="material-icons">cancel</i> Cancel</button>
                        </div>
                    </div>

                    </div>

      
      </div>
      </div>


        <div class="grid muuri">
<?        
        // load cards
        foreach ($linkiData->cards as $card) {
?>
        <div class="item h20 w20 <?=$card->data_color?> muuri-item muuri-item-shown" data-id="<?=$card->data_id?>" data-color="<?=$card->data_color?>" data-title="<?=$card->data_title?>" data-icon="<?=$card->icon?>" data-link="<?=$card->link_url?>" style="left: 0px; top: 0px; transform: translateX(0px) translateY(0px); display: block; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <!-- <a href="<?=$card->link_url?>" target="_blank"> -->
                <div class="item-content" style="opacity: 1; transform: scale(1);">
                    <div class="card">
                        <div class="card-id"><?=$card->data_id?></div>
                        <div class="card-icon">
                            <i class="material-icons"><?=$card->icon?></i>
                        </div>
                        <div class="card-title"><?=$card->data_title?></div>
                        <div class="card-remove">
                            <i class="material-icons"></i>
                        </div>
                        <div class="card-edit">
                            <i class="material-icons">edit</i>
                        </div>
                    </div>
                </div>
                <!-- </a> -->
        </div>  
<?          
        }
?>

    </div>

   
    <div class="grid-footer">
          <button class="add-more-items btn btn-primary"><i class="material-icons">&#xE145;</i>Add more items</button>       
          <button id="save-button" class="save-items btn btn-primary popup" data-badge=""><i class="material-icons">save_alt</i>Save<span class="popuptext" id="myPopup">Linki saved!</span></button>
    </div>

    </section>
    
    <footer>
      Making linkpages fun! <i class="material-icons">favorite</i> Powered by <a href="https://www.danton.nl" target="_blank">Danton</a>
    </footer>
    
    <script>
        // init script for javasettings
        var admin = true;
    </script>
    <script src="scripts/vendor/web-animations-2.3.1.min.js"></script>
    <script src="scripts/vendor/hammer-2.0.8.min.js"></script>
    <script src="scripts/vendor/muuri-0.7.0.js"></script>
    <script src="scripts/link-list.js?v=1"></script>

  </body>

</html>
