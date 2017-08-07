<?php declare(strict_types=1);

namespace Mangoweb\LatteComponents;


use Latte\Token;


class Component
{

	/**
	 * @var ComponentDefinition
	 */
	private $definition;

	/**
	 * @var array
	 */
	private $attributes;

	/**
	 * @var array
	 */
	private $children;


	public function __construct(ComponentDefinition $definition, array $attributes, array $children)
	{
		$this->definition = $definition;
		$this->attributes = $attributes;
		$this->children = $children;
	}


	public function render(): string
	{
		$fmtAttrs = $this->renderAttributes();
		$fmtChildren = implode('', $this->children); // TODO

		$captureVar = '$capture' . mt_rand(100000, 1000000-1);
		return sprintf('{capture %s}%s{/capture}{include %s%s, children=%s}',
			$captureVar, $fmtChildren, $this->definition->getTemplatePath(), $fmtAttrs, $captureVar);
	}


	private function renderAttributes(): string
	{
		$parts = [];
		foreach ($this->attributes as $token) {
			assert($token instanceof Token);
			if ($token->type === Token::HTML_ATTRIBUTE_BEGIN) {
				$parts[] = ', ';
				$parts[] = $token->name;
				$parts[] = '=';
			} elseif ($token->type === Token::TEXT) {
				$parts[] = sprintf('"%s"', $token->text);
			} elseif ($token->type === Token::MACRO_TAG) {
				$parts[] = $token->value;
			}
		}

		return implode('', $parts);
	}


}
