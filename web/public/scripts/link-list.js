/// <reference path="../node_modules/@types/jquery/index.d.ts" />

document.addEventListener('DOMContentLoaded', function () {

  //
  // Initialize stuff
  //

  var grid = null;
  var docElem = document.documentElement;
  var demo = document.querySelector('.grid-demo');
  var gridElement = demo.querySelector('.grid');
  var filterField = demo.querySelector('.filter-field');
  var searchField = demo.querySelector('.search-field');
  var sortField = demo.querySelector('.sort-field');
  var layoutField = demo.querySelector('.layout-field');
  var addItemsElement = demo.querySelector('.add-more-items');
  var saveItemsElements = demo.querySelector('.save-items');
  var filterOptions = ['red', 'blue', 'green'];
  var dragOrder = [];
  var uuid = 0;
  var filterFieldValue;
  var sortFieldValue;
  var layoutFieldValue;
  var searchFieldValue;
  
  // Initialize edit window
  var changeColorField = document.querySelector('#sel-color');
  var changeIconField = document.querySelector('#sel-icon');
  var changeTitleField = document.querySelector('#sel-title');
  var changeURLField = document.querySelector('#sel-url');
  var cancelEdit = document.querySelector('#cancelBtn');
  var saveEdit = document.querySelector('#saveBtn');

  //
  // Grid helper functions
  //

  function initDemo() {

    initGrid();
    loadIcons();

    // Reset field values.
    searchField.value = '';
    [sortField, filterField, layoutField].forEach(function (field) {
      field.value = field.querySelectorAll('option')[0].value;
    });

    // Set inital search query, active filter, active sort value and active layout.
    searchFieldValue = searchField.value.toLowerCase();
    filterFieldValue = filterField.value;
    sortFieldValue = sortField.value;
    layoutFieldValue = layoutField.value;

    // Search field binding.
    searchField.addEventListener('keyup', function () {
      var newSearch = searchField.value.toLowerCase();
      if (searchFieldValue !== newSearch) {
        searchFieldValue = newSearch;
        filter();
      }
    });

    // Filter, sort and layout bindings.
    filterField.addEventListener('change', filter);
    sortField.addEventListener('change', sort);
    layoutField.addEventListener('change', changeLayout);

    // Add/remove items bindings.
    addItemsElement.addEventListener('click', addItems);
    gridElement.addEventListener('click', function (e) {
      if (elementMatches(e.target, '.card-remove, .card-remove i')) {
        removeItem(e);
      } else if (elementMatches(e.target, '.card-edit, .card-edit i')) {
        editItem(e);
      }
    });

    // Save and change items bindings
    saveItemsElements.addEventListener('click', function () {
      saveLayout(grid);
      updateSaveCounter(true);
    });
    
    // Edit window bindings
    changeColorField.addEventListener('change',updatePlaceholderColor);
    changeTitleField.addEventListener('keyup',updatePlaceholderTitle);
    changeURLField.addEventListener('keyup',updatePlaceholderURL);
    changeIconField.addEventListener('change',updatePlaceholderIcon);
    cancelEdit.addEventListener('click',closeEditWindow);
    saveEdit.addEventListener('click',saveCard);

  }

  function initGrid() {

    var dragCounter = 0;

    grid = new Muuri(gridElement, {
//      items: generateElements(20),
      layoutDuration: 400,
      layoutEasing: 'ease',
      dragEnabled: true,
      dragSortInterval: 50,
      dragContainer: document.body,
      dragStartPredicate: function (item, event) {
        var isDraggable = sortFieldValue === 'order';
        var isRemoveAction = elementMatches(event.target, '.card-remove, .card-remove i');
        var isEditAction = elementMatches(event.target, '.card-edit, .card-edit i');
        return isDraggable && !isRemoveAction && !isEditAction ? Muuri.ItemDrag.defaultStartPredicate(item, event) : false;
      },
      dragReleaseDuration: 400,
      dragReleseEasing: 'ease'
    })
    .on('dragStart', function () {
      ++dragCounter;
      docElem.classList.add('dragging');
    })
    .on('dragEnd', function () {
      if (--dragCounter < 1) {
        docElem.classList.remove('dragging');
      }
    })
    .on('move', updateIndices)
    .on('sort', updateIndices);

  }

  function serializeLayout(grid) {
    var itemIds = grid.getItems().map(function (item) {
      var linkiElem = {};
      linkiElem['data_id'] = item.getElement().getAttribute('data-id');
      linkiElem['data_color'] = item.getElement().getAttribute('data-color');
      linkiElem['data_title'] = item.getElement().getAttribute('data-title');
      linkiElem['icon'] = item.getElement().getAttribute('data-icon');
      linkiElem['link_url'] = item.getElement().getAttribute('data-link');
      return linkiElem;
    });
    return JSON.stringify(itemIds);
  }

  function saveLayout(grid) {
    var layout = serializeLayout(grid);
    // send to PHP helper function
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "linki-save.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");    
    xhttp.onreadystatechange = function () {
      if (xhttp.readyState === 4 && xhttp.status === 200) {
          // console.log(xhttp.responseText);
          var popup = document.getElementById("myPopup");
          popup.classList.add("show");
          setTimeout(function(){
            popup.classList.remove("show");
           }, 2500);
      }
    }
    xhttp.send(layout);
  }

  function filter() {

    filterFieldValue = filterField.value;
    grid.filter(function (item) {
      var element = item.getElement();
      var isSearchMatch = !searchFieldValue ? true : (element.getAttribute('data-title') || '').toLowerCase().indexOf(searchFieldValue) > -1;
      var isFilterMatch = !filterFieldValue ? true : (element.getAttribute('data-color') || '') === filterFieldValue;
      return isSearchMatch && isFilterMatch;
    });

  }

  function sort() {

    // Do nothing if sort value did not change.
    var currentSort = sortField.value;
    if (sortFieldValue === currentSort) {
      return;
    }

    // If we are changing from "order" sorting to something else
    // let's store the drag order.
    if (sortFieldValue === 'order') {
      dragOrder = grid.getItems();
    }

    // Sort the items.
    grid.sort(
      currentSort === 'title' ? compareItemTitle :
      currentSort === 'color' ? compareItemColor :
      dragOrder
    );

    // Update indices and active sort value.
    updateIndices();
    sortFieldValue = currentSort;

  }

  function addItems() {

    // Generate new elements.
    var newElems = generateElements(1);

    // Set the display of the new elements to "none" so it will be hidden by
    // default.
    newElems.forEach(function (item) {
      item.style.display = 'none';
    });

    // Add the elements to the grid.
    var newItems = grid.add(newElems);

    // Update UI indices.
    updateIndices();

    // Sort the items only if the drag sorting is not active.
    if (sortFieldValue !== 'order') {
      grid.sort(sortFieldValue === 'title' ? compareItemTitle : compareItemColor);
      dragOrder = dragOrder.concat(newItems);
    }

    // Finally filter the items.
    filter();
  }

  function removeItem(e) {

    var elem = elementClosest(e.target, '.item');
    grid.hide(elem, {onFinish: function (items) {
      var item = items[0];
      grid.remove(item, {removeElements: true});
      if (sortFieldValue !== 'order') {
        var itemIndex = dragOrder.indexOf(item);
        if (itemIndex > -1) {
          dragOrder.splice(itemIndex, 1);
        }
      }
    }});
    updateIndices();

  }

  function editItem (e) {
      var elem = elementClosest(e.target, '.item');
      inputItem (elem.getAttribute('data-id'),elem.getAttribute('data-icon'), elem.getAttribute('data-title'), elem.getAttribute('data-link'), elem.getAttribute('data-color'))
  }

  function inputItem (elementDataID, elementIcon, elementTitle, elementURL, elementColor) {
     // first fill in the placeholder

     document.getElementById('ph-dataid').innerHTML=elementDataID;
     document.getElementById('sel-dataid').value=elementDataID;
     var element = document.getElementById('placeholder');
     element.classList.remove("red","green","blue");
     element.classList.add(elementColor);
     document.getElementById("sel-color").value=elementColor;
     document.getElementById('ph-title').innerHTML=elementTitle;    
     document.getElementById('sel-title').value=elementTitle;
     document.getElementById('ph-link').href=elementURL;
     document.getElementById('sel-url').value=elementURL;
     document.getElementById('ph-icon').innerHTML='<i class="material-icons">'+elementIcon+'</i>';
     document.getElementById("sel-icon").value=elementIcon;
         
    // then show the edit window
     var element = document.getElementById('myModal');
     element.classList.remove("hideEdit");
     element.classList.add("showEdit");
  }


  function changeLayout() {

    layoutFieldValue = layoutField.value;
    grid._settings.layout = {
      horizontal: false,
      alignRight: layoutFieldValue.indexOf('right') > -1,
      alignBottom: layoutFieldValue.indexOf('bottom') > -1,
      fillGaps: layoutFieldValue.indexOf('fillgaps') > -1
    };
    grid.layout();

  }


  //
  // Generic helper functions
  //

  function generateElements(amount) {

    var ret = [];

    for (var i = 0; i < amount; i++) {
      ret.push(generateElement(
        ++uuid,
        "New Item",
        "#",
        getRandomItem(filterOptions),
        "help_outline",
        20,
        20
      ));
    }

    return ret;

  }

  function generateElement(id, title, link, color, icon, width, height) {

    var itemElem = document.createElement('div');
    var classNames = 'item h' + height + ' w' + width + ' ' + color;
    var itemTemplate = '' +
        '<div class="' + classNames + '" data-id="' + id + '" data-color="' + color + '" data-title="' + title + '" data-icon="' + icon + '" data-link="' + link + '">' +
          '<div class="item-content">' +
            '<div class="card">' +
              '<div class="card-id">' + id + '</div>' +
              '<div class="card-icon"><i class="material-icons">' + icon + '</i></div>'+              
              '<div class="card-title">' + title + '</div>' +
              '<div class="card-remove"><i class="material-icons">&#xE5CD;</i></div>' +
              '<div class="card-edit"><i class="material-icons">edit</i></div>' +
            '</div>' +
          '</div>' +
        '</div>';

    itemElem.innerHTML = itemTemplate;
    return itemElem.firstChild;

  }

  function getRandomItem(collection) {

    return collection[Math.floor(Math.random() * collection.length)];

  }

  function compareItemTitle(a, b) {

    var aVal = a.getElement().getAttribute('data-title') || '';
    var bVal = b.getElement().getAttribute('data-title') || '';
    return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;

  }

  function compareItemColor(a, b) {

    var aVal = a.getElement().getAttribute('data-color') || '';
    var bVal = b.getElement().getAttribute('data-color') || '';
    return aVal < bVal ? -1 : aVal > bVal ? 1 : compareItemTitle(a, b);

  }

  function updateIndices() {

    grid.getItems().forEach(function (item, i) {
      item.getElement().setAttribute('data-id', i + 1);
      item.getElement().querySelector('.card-id').innerHTML = i + 1;
    });
    updateSaveCounter(false);
  }

  function elementMatches(element, selector) {

    var p = Element.prototype;
    return (p.matches || p.matchesSelector || p.webkitMatchesSelector || p.mozMatchesSelector || p.msMatchesSelector || p.oMatchesSelector).call(element, selector);

  }

  function elementClosest(element, selector) {

    if (window.Element && !Element.prototype.closest) {
      var isMatch = elementMatches(element, selector);
      while (!isMatch && element && element !== document) {
        element = element.parentNode;
        isMatch = element && element !== document && elementMatches(element, selector);
      }
      return element && element !== document ? element : null;
    }
    else {
      return element.closest(selector);
    }

  }

function loadIcons() {
  var selIcons = document.getElementById('sel-icon');
  loadJSON('scripts/MaterialIcons-Regular.ijmap',
      function(data) { 
        var element = data.icons;
        for (var key in element){
            var option = document.createElement("option");
            option.text = element[key].name.toLowerCase().replace(/ /g,"_");;
            selIcons.add(option);
        }
     }
  );
}

function loadJSON(path, success, error)
{
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (success)
                    success(JSON.parse(xhr.responseText));
            } else {
                if (error)
                    error(xhr);
            }
        }
    };
    xhr.open("GET", path, true);
    xhr.send();
}

// Edit window helper functions
function updatePlaceholderColor() {
  var element = document.getElementById('placeholder');
  var selColor = document.getElementById('sel-color');
  var value = selColor.options[selColor.selectedIndex].value;
  element.classList.remove("red","green","blue");
  element.classList.add(value);
}

function updatePlaceholderTitle() {
  var value = document.getElementById('sel-title').value;
  document.getElementById('ph-title').innerHTML=value;
}

function updatePlaceholderURL() {
  var value = document.getElementById('sel-url').value;
  if (!value.match(/^[a-zA-Z]+:\/\//))  {
    value = 'https://' + value;
  }
  document.getElementById('ph-link').href=value;
}

function updatePlaceholderIcon() {
  var selIcon = document.getElementById('sel-icon');
  var value = selIcon.options[selIcon.selectedIndex].value;
  document.getElementById('ph-icon').innerHTML='<i class="material-icons">'+value+'</i>';
}

function closeEditWindow() {
  var element = document.getElementById('myModal');
  element.classList.remove("showEdit");
  element.classList.add("hideEdit");
}

function saveCard() {
  // find edited data-id in the placeholder
  var dataID = document.getElementById('sel-dataid').value;
  // find the card by data-id
  var nodelist = document.querySelectorAll('[data-id="' + dataID + '"]');
  // data-id is unique so there is only one match
  var targetElement = nodelist[0];

  // match the selected card with the (updated) placeholder
  var selColor = document.getElementById('sel-color');
  var selIcon = document.getElementById('sel-icon');
  var selUrl = document.getElementById('sel-url').value;
  if (!selUrl.match(/^[a-zA-Z]+:\/\//))  {
    selUrl = 'https://' + selUrl;
  }
  
  // alter the data attributes
  targetElement.setAttribute('data-icon', selIcon.options[selIcon.selectedIndex].value);
  targetElement.setAttribute('data-title', document.getElementById('sel-title').value);
  targetElement.setAttribute('data-link', selUrl);
  targetElement.setAttribute('data-color', selColor.options[selColor.selectedIndex].value);
  // match the HTML output of the card
  targetElement.querySelector('.card-icon').innerHTML='<i class="material-icons">' + selIcon.options[selIcon.selectedIndex].value + '<i>';
  targetElement.querySelector('.card-title').innerHTML=document.getElementById('sel-title').value;
  // targetElement.querySelector('.card-url').innerHTML=selUrl;
  targetElement.classList.remove("red","green","blue");
  targetElement.classList.add(selColor.options[selColor.selectedIndex].value);
  
  updateSaveCounter(false);
  closeEditWindow();
}


function updateSaveCounter(reset) {
  var saveButton = document.getElementById('save-button');
  var dataBadge = Number(saveButton.getAttribute('data-badge'));
  var x = dataBadge+1;
  if (!reset) {
    saveButton.setAttribute('data-badge',x.toString());  
  } else {
    // reset the counter and hide it
    saveButton.setAttribute('data-badge',"");
  }
}
  initDemo();
});