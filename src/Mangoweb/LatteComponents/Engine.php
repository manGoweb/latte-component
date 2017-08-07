<?php declare(strict_types=1);

namespace Mangoweb\LatteComponents;

use Latte\Parser;
use Latte\Token;


class Engine
{

	/** @var ComponentDefinition[] */
	private $components = [];

	/**
	 * @var Parser
	 */
	private $parser;


	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
	}


	public function render(string $content): string
	{
		$tokens = $this->parser->parse($content);
		$walker = new TokenWalker($tokens);
		$factory = new HierarchyFactory($walker, $this->components);

		$hierarchy = $factory->getHierarchy();

		return $this->renderHierarchy($hierarchy);
	}


	public function renderHierarchy(array $nodes): string
	{
		$parts = [];

		foreach ($nodes as $node) {
			if ($node instanceof Component) {
				$parts[] = $node->render();
			} elseif ($node instanceof Token) {
				$parts[] = $node->text;
			}
		}

		return implode('', $parts);
	}


	public function registerComponent(ComponentDefinition $component): void
	{
		$key = $component->getElement();
		if (array_key_exists($key, $this->components)) {
			throw new EngineException("Component element '$key' is already registered");
		}
		$this->components[$component->getElement()] = $component;
	}

}
