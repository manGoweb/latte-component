<?php declare(strict_types=1);

namespace Mangoweb\LatteComponents;

use Latte\Token;


class HierarchyFactory
{

	/**
	 * @var TokenWalker
	 */
	private $walker;

	/**
	 * @var ComponentDefinition[]
	 */
	private $componentDefinitions;


	public function __construct(TokenWalker $walker, array $componentDefinitions)
	{
		$this->walker = $walker;
		$this->componentDefinitions = $componentDefinitions;
	}


	private function isRegisteredComponent(string $name): ?ComponentDefinition
	{
		$name = mb_strtolower($name);
		return $this->componentDefinitions[$name] ?? NULL;
	}


	/**
	 * @return array [array $tokens, ComponentDefinition $component]
	 */
	public function getUntilComponent(): array
	{
		$output = [];
		while ($this->walker->valid()) {
			$tokens = $this->walker->getUntilType(Token::HTML_TAG_BEGIN);
			array_push($output, ...$tokens);

			$htmlOpen = $this->walker->next();
			if ($htmlOpen === NULL) {
				return [$output, NULL];
			}
			$definition = $this->isRegisteredComponent($htmlOpen->name);
			if ($definition === NULL) {
				$output[] = $htmlOpen;
			} else {
				return [$output, $definition];
			}
		};
		return [$output, NULL];
	}


	public function getUntilComponentEnd(): array
	{
		return $this->walker->getUntilSameLevelCloseTag();
	}


	/**
	 * @return array|Token[]|Component[]
	 */
	public function getHierarchy(): array
	{
		$output = [];
		while ($this->walker->valid()) {
			[$tokens, $definition] = $this->getUntilComponent();

			array_push($output, ...$tokens);
			if ($definition === NULL) {
				return $output;
			}

			$attributes = $this->walker->getUntilType(Token::HTML_TAG_END);
			$this->walker->next(); // discard the END token

			$children = $this->getUntilComponentEnd();
			// recurse for components in child nodes
			$walker = new TokenWalker($children);
			$hierarchyFactory = new static($walker, $this->componentDefinitions);

			$hierarchy = $hierarchyFactory->getHierarchy();
			$output[] = new Component($definition, $attributes, [$this->renderHierarchy($hierarchy)]);
			$this->walker->next(); // discard the END token
		}

		return $output;
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

}
