var a=$("[data-content]"),n=$("[data-toc]");if(a.length&&n.length){var t=a.find("h2, h3"),e=$('<ul id="toc" class="nav flex-column nav--page ps-2 small"></ul>');t.each((function(a,n){var t,c=$(n),l=c.text(),s=(t=l)&&t.match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g).map((function(a){return a.toLowerCase()})).join("-"),o="";o="H2"===n.tagName?"ps-0":"ps-3 link-secondary";var i=$('<li class="nav-item"><a class="nav-link '.concat(o,' py-1" href="#').concat(s,'">').concat(n.textContent,"</a></li>"));e.append(i),c.before($('<div id="'.concat(s,'" style="transform: translateY(-3rem)"></div>'))),c.append($('<span class="anchor-icon fa fa-link-simple"></span>')),c.wrap($('<a class="heading-link" href="#'.concat(s,'"></a>')))})),n.append(e)}$(".article-content table").addClass("table table-bordered");
//# sourceMappingURL=doc.js.map
