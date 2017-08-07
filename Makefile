SRC := $(shell find $(CURDIR)/src -name '*.php')
TEST_SRC := $(shell find $(CURDIR)/tests -name '*.php')

composer.lock: $(SRC)
	composer dump-autoload --classmap-authoritative --optimize

test: composer.lock
	vendor/bin/tester tests/
