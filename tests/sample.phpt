<?php declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$template = /** @lang Latte */ <<<'EOC'
before <alpha a="b" c="{$d}">lorem ipsum dolor {$neco}</alpha> after
EOC;

$expected = /** @lang Latte */ <<<'EOC'
before {capture $capture195845}lorem ipsum dolor {$neco}{/capture}{include componentsDir/alpha.latte, a="b", c=$d, children=$capture195845} after
EOC;


$parser = new \Latte\Parser();
$engine = new \Mangoweb\LatteComponents\Engine($parser);
$engine->registerComponent(new \Mangoweb\LatteComponents\ComponentDefinition('alpha', 'componentsDir/alpha.latte'));
\Tester\Assert::same($expected, $engine->render($template));
