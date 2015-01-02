/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

$(document).ready(function()
{
	var article = $('#main-body .article-content');

	var h1s = article.find('h1');
	var lists = [];
	var nav = $('<ul class="uk-nav"></ul>');

	if (!h1s.length)
	{
		return;
	}

	h1s.each(function(i)
	{
		var h1 = $(this);

		var title = h1.text();

		title = title.toLowerCase();
		title = title.replace(/\s/g, '-');

		h1.attr('id', title);

		nav.append($('<li><a href="#' + title + '">' + h1.text() + '</a></li>')[0]);

		h1.prepend($('<a class="h1-link" href="#' + title + '">#</a>'));
	});

	var table = $('<div class="table-of-content"></div>');

	table.prepend(nav).prepend('<h1>Table of Content</h1>');

	$('.article-content').prepend(table);
});
