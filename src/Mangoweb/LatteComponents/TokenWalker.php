<?php declare(strict_types=1);

namespace Mangoweb\LatteComponents;

use Latte\Token;


class TokenWalker
{

	/** @var \ArrayIterator */
	private $iterator;


	public function __construct(array $tokens)
	{
		$this->iterator = new \ArrayIterator($tokens);
	}


	public function valid(): bool
	{
		return $this->iterator->valid();
	}


	public function next(): ?Token
	{
		$token = $this->iterator->valid() ? $this->iterator->current() : NULL;
		$this->iterator->next();
		return $token;
	}


	public function getUntilType(string $type): array
	{
		$tokens = [];
		while ($this->iterator->valid()) {
			$token = $this->iterator->current();
			assert($token instanceof Token);

			if ($token->type === $type) {
				break;
			}
			$tokens[] = $token;
			$this->iterator->next();
		}
		return $tokens;
	}


	public function getUntilSameLevelCloseTag(): array
	{
		$tagNestingLevel = 1;
		$tokens = [];

		while ($this->iterator->valid()) {
			$token = $this->iterator->current();
			assert($token instanceof Token);

			if ($token->type === Token::HTML_TAG_BEGIN) {
				if ($token->closing) {
					$tagNestingLevel -= 1;
					if ($tagNestingLevel === 0) {
						// read until ">" of </sth>
						$this->getUntilType(Token::HTML_TAG_END);
						break;
					}
				} else {
					$tagNestingLevel += 1;
				}
			}
			$tokens[] = $token;
			$this->iterator->next();
		}
		return $tokens;
	}

}
