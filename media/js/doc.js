/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

$(document).ready(function()
{
	var article = $('#main-body .article-content');

	var titles = article.find('h2, h3');
	var lists = [];
	var nav = $('<ul class="uk-nav"></ul>');

	if (!titles.length)
	{
		return;
	}

	titles.each(function(i)
	{
		var $this = $(this);

		var title = $this.text();

		title = title.toLowerCase();
		title = title.replace(/\s/g, '-');

		$this.prepend($(`<span id="${title}" class="title-anchor"></span>`));

        if ($this.prop("tagName") == 'H2')
        {
            nav.append($('<li><a href="#' + title + '">' + $this.text() + '</a></li>')[0]);
        }

        $this.prepend($('<a class="title-link" href="#' + title + '">#</a>'));
    });

	var table = $('<div class="table-of-content"></div>');

	table.prepend(nav).prepend('<h2>Table of Content</h2>');

	$('.article-content').prepend(table);
});
