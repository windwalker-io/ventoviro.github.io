$(document).ready(function () {
  // Init Material
  $.material.init();

  // Smooth scroll
  setTimeout(function () {
    $('body a').smoothScroll({
      afterScroll: function (event) {
        document.location.hash = this.hash
      }
    });
  }, 500);

  // Table style
  // Convert table style
  var tables = $('.article-content table');

  tables.addClass('table').addClass('table-striped');

  // Navbar
  var navTop;
  var hasColor = false;
  var nav = $('#nav .navbar');

  processScrollInit();
  processScroll();

  $(window).on('resize', processScrollInit);
  $(window).on('scroll', processScroll);

  function processScrollInit() {
    if (nav.length) {
      navTop = nav.length && 60;

      // Only apply the scrollspy when the toolbar is not collapsed
      if (document.body.clientWidth > 480) {
        // $('.subhead-collapse').height(nav.height());
        nav.scrollspy({offset: {top: nav.offset().top}});
      }
    }
  }

  function processScroll() {
    if (nav.length) {
      var scrollTop = $(window).scrollTop();

      if (scrollTop >= navTop && !hasColor) {
        hasColor = true;
        nav.addClass('navbar-primary');
      } else if (scrollTop <= navTop && hasColor) {
        hasColor = false;
        nav.removeClass('navbar-primary');
      }
    }
  }
});
