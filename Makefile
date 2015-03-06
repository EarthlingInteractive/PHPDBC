default: run-unit-tests

.PHONY: \
	clean \
	default \
	test-dependencies \
	run-unit-tests

clean:
	rm -rf composer.lock vendor

composer.lock: | composer.json
	composer install

vendor: composer.lock
	composer install

test-dependencies: vendor

run-unit-tests: test-dependencies
	phpunit --bootstrap vendor/autoload.php test
