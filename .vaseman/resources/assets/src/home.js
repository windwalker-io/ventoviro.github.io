/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

const $win = $(window);
const $nav = $('.l-main-header');

$win.on('scroll', () => {
    if ($win.scrollTop() > 200) {
        $nav.fadeIn();
    } else {
        $nav.fadeOut();
    }
});
