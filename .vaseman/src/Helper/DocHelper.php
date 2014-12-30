<?php

namespace Vaseman\Helper;

use Windwalker\Core\View\Helper\AbstractHelper;

class DocHelper extends AbstractHelper
{
	public function getPath($paths)
	{
		array_shift($paths);

		$paths = implode('/', $paths);

		return \Windwalker\Filesystem\File::stripExtension($paths);
	}
}
