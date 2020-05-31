.PHONY: build test rebuild prepare-local-env-dev run

prepare-local-env-dev:
	cp .env.dist .env

build:
	docker-compose run php composer install
	docker-compose run php composer dump-autoload

test:
	docker-compose run php mkdir -p build/test_results/phpunit
	docker-compose run php ./vendor/bin/simple-phpunit --exclude-group='disabled' --log-junit build/test_results/phpunit/junit.xml tests
	docker-compose run php ./vendor/bin/behat --format=progress -v

rebuild:
	docker-compose build --pull --force-rm --no-cache
	make build

run:
	docker-compose run php php bin/console vending_machine:shell_input_command  $(filter-out $@,$(MAKECMDGOALS))

%:
	@:

