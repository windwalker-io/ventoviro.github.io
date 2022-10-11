/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */
var $win = $(window);
var $nav = $('.l-main-header');
$win.on('scroll', function () {
  if ($win.scrollTop() > 200) {
    $nav.fadeIn();
  } else {
    $nav.fadeOut();
  }
});
//# sourceMappingURL=home.js.map
