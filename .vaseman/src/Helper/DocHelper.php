<?php

namespace Vaseman\Helper;

use Windwalker\Core\View\Helper\AbstractHelper;
use Windwalker\Filesystem\File;
use Windwalker\Registry\Registry;

class DocHelper extends AbstractHelper
{
	public function getPath($paths, $version = null)
	{
		array_shift($paths);

		if ($version)
		{
			array_shift($paths);
		}

		$paths = implode('/', $paths);

		return \Windwalker\Filesystem\File::stripExtension($paths);
	}

	public function getVersionPath($paths, $version, $config)
	{
		$config = new Registry($config);
		$config->setSeparator('/');

		if ($config['redirect/' . $version])
		{
			$path = 'documentation/' . $version . '/' . $config['redirect/' . $version];
		}
		else
		{
			$paths[1] = $version;
			$path = File::stripExtension(implode('/', $paths));
		}

		return $path . '.html';
	}
}
