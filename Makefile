
dev\:fixtures:
	php bin/console doctrine:database:drop --if-exists --force
	php bin/console doctrine:database:create
	php bin/console doctrine:schema:create
	php bin/console doctrine:fixtures:load --append
	php bin/console cache:clear

test\:fixtures:
	php bin/console doctrine:database:drop --if-exists --force --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:create --env=test
	php bin/console doctrine:fixtures:load --append --env=test
	php bin/console cache:clear --env=test

clear:
	php bin/console cache:clear
