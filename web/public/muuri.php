
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Muuri - Responsive, sortable, filterable and draggable grid layouts</title>
    <meta name="description" content="Muuri is a JavaScript library that creates responsive, sortable, filterable and draggable grid layouts.">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="author" content="Niklas Rämö">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Sans:400,300,300italic,400italic,500,500italic,700,700italic">
    <link rel="stylesheet" href="styles/main.css?v=5">
    <link rel="stylesheet" href="styles/demo-grid.css?v=5">
    <link rel="stylesheet" href="styles/demo-kanban.css?v=5">
  </head>
  <body>

    <header>
    </header>

    <section class="grid-demo">

      <h2 class="section-title"><span>Grid Demo</span></h2>

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
      </div>


        <div class="grid muuri">
            <div class="item h20 w20 red muuri-item muuri-item-shown" data-id="1" data-color="red" data-title="om" style="left: 0px; top: 0px; transform: translateX(0px) translateY(0px); display: block; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <div class="item-content" style="opacity: 1; transform: scale(1);">
                    <div class="card">
                        <div class="card-id">1</div>
                        <div class="card-icon">
                            <i class="material-icons">android</i>
                        </div>
                        <div class="card-title">Somtoday</div>
                        <div class="card-remove">
                            <i class="material-icons"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="item h20 w20 green muuri-item muuri-item-shown" data-id="2" data-color="green" data-title="om" style="left: 0px; top: 0px; transform: translateX(0px) translateY(0px); display: block; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <div class="item-content" style="opacity: 1; transform: scale(1);">
                    <div class="card">
                        <div class="card-id">2</div>
                        <div class="card-icon">
                            <i class="material-icons">extension</i>
                        </div>
                        <div class="card-title">Puzzel</div>
                        <div class="card-remove">
                            <i class="material-icons"></i>
                        </div>
                    </div>
                </div>
            </div>


            <div class="item h20 w20 blue muuri-item muuri-item-shown" data-id="3" data-color="blue" data-title="om" style="left: 0px; top: 0px; transform: translateX(0px) translateY(0px); display: block; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <div class="item-content" style="opacity: 1; transform: scale(1);">
                    <div class="card">
                        <div class="card-id">3</div>
                        <div class="card-icon">
                            <i class="material-icons">face</i>
                        </div>
                        <div class="card-title">Gezicht</div>
                        <div class="card-remove">
                            <i class="material-icons"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="item h20 w20 green muuri-item muuri-item-shown" data-id="4" data-color="green" data-title="om" style="left: 0px; top: 0px; transform: translateX(0px) translateY(0px); display: block; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <div class="item-content" style="opacity: 1; transform: scale(1);">
                    <div class="card">
                        <div class="card-id">4</div>
                        <div class="card-icon">
                            <i class="material-icons">favorite</i>
                        </div>
                        <div class="card-title">Schoolmail</div>
                        <div class="card-remove">
                            <i class="material-icons"></i>
                        </div>
                    </div>
                </div>
            </div>

    </div>

   
     <div class="grid-footer">
        <button class="add-more-items btn btn-primary"><i class="material-icons">&#xE145;</i>Add more items</button>
      </div>

    </section>

    
    
    <footer>
    </footer>

    <script src="scripts/vendor/web-animations-2.3.1.min.js"></script>
    <script src="scripts/vendor/hammer-2.0.8.min.js"></script>
    <script src="scripts/vendor/muuri-0.7.0.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="scripts/link-list.js?v=1"></script>

  </body>

</html>
