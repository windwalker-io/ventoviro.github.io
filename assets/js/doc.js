/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */
var $content = $('[data-content]');
var $toc = $('[data-toc]');

if ($content.length && $toc.length) {
  var $headings = $content.find('h2, h3');
  var $nav = $('<ul id="toc" class="nav flex-column nav--page ps-2 small"></ul>');
  $headings.each(function (i, heading) {
    var $heading = $(heading);
    var text = $heading.text();
    var hash = toKebabCase(text);
    var className = '';

    if (heading.tagName === 'H2') {
      className = 'ps-0';
    } else {
      className = 'ps-3 link-secondary';
    }

    var $item = $("<li class=\"nav-item\"><a class=\"nav-link ".concat(className, " py-1\" href=\"#").concat(hash, "\">").concat(heading.textContent, "</a></li>"));
    $nav.append($item); // H2

    $heading.before($("<div id=\"".concat(hash, "\" style=\"transform: translateY(-3rem)\"></div>")));
    $heading.append($("<span class=\"anchor-icon fa fa-link-simple\"></span>"));
    $heading.wrap($("<a class=\"heading-link\" href=\"#".concat(hash, "\"></a>")));
  });
  $toc.append($nav);
} // Table


$('.article-content table').addClass('table table-bordered');

function toKebabCase(str) {
  return str && str.match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g).map(function (x) {
    return x.toLowerCase();
  }).join('-');
}
//# sourceMappingURL=doc.js.map
