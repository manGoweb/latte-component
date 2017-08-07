<?php declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$template = /** @lang Latte */ <<<'EOC'
before<alpha>in-alpha before<beta>in-beta</beta>in-alpha after</alpha>after
EOC;

$expected = /** @lang Latte */ <<<'EOC'
before{capture $capture195845}in-alpha before<beta>in-beta</beta>in-alpha after{/capture}{include componentsDir/alpha.latte, children=$capture195845}>after
EOC;


$parser = new \Latte\Parser();
$engine = new \Mangoweb\LatteComponents\Engine($parser);
$engine->registerComponent(new \Mangoweb\LatteComponents\ComponentDefinition('alpha', 'componentsDir/alpha.latte'));
\Tester\Assert::same($expected, $engine->render($template));
