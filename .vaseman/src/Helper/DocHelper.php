<?php

namespace Vaseman\Helper;

use Windwalker\Core\View\Helper\AbstractHelper;
use Windwalker\Filesystem\File;

class DocHelper extends AbstractHelper
{
	public function getPath($paths)
	{
		array_shift($paths);
		array_shift($paths);

		$paths = implode('/', $paths);

		return \Windwalker\Filesystem\File::stripExtension($paths);
	}

	public function getVersionPath($paths, $version)
	{
		$paths[1] = $version;

		return File::stripExtension(implode('/', $paths)) . '.html';
	}
}
