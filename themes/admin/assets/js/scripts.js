$(document).ready(function () {
  // Initializes search overlay plugin.
  // Replace onSearchSubmit() and onKeyEnter() with 
  // your logic to perform a search and display results
  $('[data-pages="search"]').search({
    searchField: '#overlay-search',
    closeButton: '.overlay-close',
    suggestions: '#overlay-suggestions',
    brand: '.brand',
    onSearchSubmit: function (searchString) {
      console.log("Search for: " + searchString);
    },
    onKeyEnter: function (searchString) {
      console.log("Live search for: " + searchString);
      var searchField = $('#overlay-search');
      var searchResults = $('.search-results');
      clearTimeout($.data(this, 'timer'));
      searchResults.fadeOut("fast");
      var wait = setTimeout(function () {
        searchResults.find('.result-name').each(function () {
          if (searchField.val().length != 0) {
            $(this).html(searchField.val());
            searchResults.fadeIn("fast");
          }
        });
      }, 500);
      $(this).data('timer', wait);
    }
  });

  var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
// Success color: #10CFBD
  elems.forEach(function (html) {
    var switchery = new Switchery(html, {color: '#10CFBD'});
  });

  $.minicolors = {
    defaults: {
      animationSpeed: 50,
      animationEasing: 'swing',
      change: null,
      changeDelay: 0,
      control: 'hue',
      defaultValue: '',
      format: 'hex',
      hide: null,
      hideSpeed: 100,
      inline: false,
      keywords: '',
      letterCase: 'lowercase',
      opacity: false,
      position: 'bottom right',
      show: null,
      showSpeed: 100,
      theme: 'bootstrap',
      swatches: []
    }
  };

  //calendar

  $('#myCalendar').pagescalendar({
    ui: {
      //Year Selector
      year: {
        visible: true,
        format: 'YYYY',
        startYear: '2000',
        endYear: moment().add(10, 'year').format('YYYY'),
        eventBubble: true
      },
      //Month Selector
      month: {
        visible: true,
        format: 'MMM',
        eventBubble: true
      },
      dateHeader: {
        format: 'MMMM YYYY, D dddd',
        visible: false,
      },
      //Mini Week Day Selector
      week: {
        day: {
          format: 'D'
        },
        header: {
          format: 'dd'
        },
        eventBubble: true,
        startOfTheWeek: '0',
        endOfTheWeek: '6'
      },
      //Week view Grid Options
      grid: {
        dateFormat: 'D dddd',
        timeFormat: 'h A',
        eventBubble: true,
        scrollToFirstEvent: false,
        scrollToAnimationSpeed: 300,
        scrollToGap: 20
      }
    },
    eventObj: {
      editable: true
    },
    view: 'month',
    now: null,
    locale: 'es',
    //Event display time format
    timeFormat: 'h:mm a',
    minTime: 0,
    maxTime: 24,
    dateFormat: 'MMMM Do YYYY',
    slotDuration: '30', //In Mins : supports 15, 30 and 60
    events: [],
    eventOverlap: false,
    weekends: true,
    disableDates: [],
  })



})