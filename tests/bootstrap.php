<?php

require __DIR__ . '/../vendor/autoload.php';

mt_srand(1);

function trimLines(string $lines): string {
	return preg_replace('~(^|\n)\s*~m', '', $lines);
}
function assertRenders(string $expected, string $template) {
	global $engine;
	\Tester\Assert::same(trimLines($expected), $engine->render(trimLines($template)));
}

Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT);
Tester\Environment::setup();
\Tracy\Debugger::$maxDepth += 5;
