<?php declare(strict_types=1);

namespace Mangoweb\LatteComponents;


class ComponentDefinition
{

	/**
	 * @var string
	 */
	private $element;

	/**
	 * @var string
	 */
	private $templatePath;


	public function __construct(string $element, string $templatePath)
	{
		$this->element = mb_strtolower($element);
		$this->templatePath = $templatePath;
	}


	public function getElement(): string
	{
		return $this->element;
	}


	public function getTemplatePath(): string
	{
		return $this->templatePath;
	}

}
