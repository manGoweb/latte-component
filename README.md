Latte Components
================
[![CircleCI](https://circleci.com/gh/manGoweb/latte-component.svg?style=svg&circle-token=3aab1306934e45c8e37cee3b63eef200039390d5)](https://circleci.com/gh/manGoweb/latte-component)

Nette\Latte preprocessor.

```latte
before <alpha a="b" c="{$d}">content {$foo}</alpha> after
```

into

```latte
before {capture $capture195845}content {$foo}{/capture}
{include componentsDir/alpha.latte, a="b", c=$d, children=$capture195845} after
```
