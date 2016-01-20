config_files = test/dbc.json

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

${config_files}: %: | %.example
	cp "$|" "$@"

test-dependencies: vendor ${config_files}

test/db-scripts/create-database.sql: test/dbc.json vendor
	mkdir -p test/db-scripts
	vendor/bin/generate-create-database-sql "$<" >"$@"
test/db-scripts/drop-database.sql: test/dbc.json vendor
	mkdir -p test/db-scripts
	vendor/bin/generate-drop-database-sql "$<" >"$@"

create-database: %: test/db-scripts/%.sql
	cat '$<' | sudo -u postgres psql -v ON_ERROR_STOP=1
drop-database: %: test/db-scripts/%.sql
	cat '$<' | sudo -u postgres psql -v ON_ERROR_STOP=1

run-unit-tests: test-dependencies
	phpunit --bootstrap test/phpunit-bootstrap.php test
