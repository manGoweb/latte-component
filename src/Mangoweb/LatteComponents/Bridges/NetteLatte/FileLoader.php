<?php declare(strict_types=1);

namespace Mangoweb\LatteComponents\Bridges\NetteLatte;

use Latte\Loaders\FileLoader as LatteFileLoader;
use Mangoweb\LatteComponents\Engine;


class FileLoader extends LatteFileLoader
{

	/**
	 * @var Engine
	 */
	private $engine;


	public function __construct(Engine $engine)
	{
		$this->engine = $engine;
	}


	public function getContent($file)
	{
		$content = parent::getContent($file);
		return $this->engine->render($content);
	}

}
