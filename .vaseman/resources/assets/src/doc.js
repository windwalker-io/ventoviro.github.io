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
  const $nav = $('<ul id="toc" class="nav flex-column nav--page ps-2 small"></ul>');

  $headings.each((i, heading) => {
    const $heading = $(heading);
    const text = $heading.text();
    const hash = toKebabCase(text);
    let className = '';

    if (heading.tagName === 'H2') {
      className = 'ps-0';
    } else {
      className = 'ps-3 link-secondary';
    }

    const $item = $(`<li class="nav-item"><a class="nav-link ${className} py-1" href="#${hash}">${heading.textContent}</a></li>`);
    $nav.append($item);

    // H2
    $heading.before($(`<div id="${hash}" style="transform: translateY(-3rem)"></div>`));
    $heading.append($(`<span class="anchor-icon fa fa-link-simple"></span>`));
    $heading.wrap($(`<a class="heading-link" href="#${hash}"></a>`));
  });

  $toc.append($nav);
}

// Table
$('.article-content table').addClass('table table-bordered');

function toKebabCase(str) {
  return str && str.match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
    .map(x => x.toLowerCase())
    .join('-');
}
