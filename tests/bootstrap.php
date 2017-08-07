<?php

require __DIR__ . '/../vendor/autoload.php';

mt_srand(1);

Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT);
Tester\Environment::setup();
\Tracy\Debugger::$maxDepth += 5;
