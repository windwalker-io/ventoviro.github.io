/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

const $content = $('[data-content]');
const $toc = $('[data-toc]');

if ($content.length && $toc.length) {
  const $headings = $content.find('h2, h3');
  const $nav = $('<ul class="nav flex-column nav--page ps-2 small"></ul>');

  $headings.each((i, h2) => {
    const $h2 = $(h2);
    const text = $h2.text();
    const hash = toKebabCase(text);
    const $item = $(`<li class="nav-item"><a href="#${hash}">${h2.textContent}</a></li>`);
    $nav.append($item);

    // H2
    $h2.before($(`<div id="${hash}" style="transform: translateY(-3rem)"></div>`));
    $h2.append($(`<span class="anchor-icon fa fa-link-simple"></span>`));
    $h2.wrap($(`<a class="heading-link" href="#${hash}"></a>`));
  });

  $toc.append($nav);
}

function toKebabCase(str) {
  return str && str.match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
    .map(x => x.toLowerCase())
    .join('-');
}