# Welcome to TodoList docs contributing guide

## Issues

### Create a new issue

If you want to submit a new issue, create a new one with proper labels.

### Solve an issue

If you want to solve an issue, create a new pull request with a fix by following the rules described below.

## Pull Request
To submit a new pull request you have to follow some rules:
*   Write and run unit and functionnal tests to expect at least 70% code coverage.
*   [PSR-1 and PSR-4 recommandations](https://www.php-fig.org/psr/#numerical-index)
*   [The Symfony Framework Best Practices](https://symfony.com/doc/current/best_practices.html)

### Project structure
*   bin ( binary files )

*   config ( config folder of symfony framework )

*   migrations ( database configuration files)

*   public ( css, javascript... )

*   src

    *   Controller ( controllers )
    *   DataFixtures ( fixtures for dataset )
    *   Entity ( entities )
    *   Form ( form types )
    *   Repository ( repositories )
    *   Security ( access control files )
    *   Service ( services )
    
*   templates ( twig files )

*   tests

    *   Controller ( functional tests for controllers )

    *   Service ( unit tests for services )

    *   Type ( unit test for forms )

### Test Environment
To write tests, you need to create an environment test for your database.

On your terminal
*   Create .env.test.local files
*   Update .env.test.local file with your database server info
```text
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```
*   Create your test database
```text
php bin/console doctrine:database:create --env=test
```
*   Create all tables to your test database
```text
php bin/console doctrine:migrations:migrate --env=test
```
*   Insert sample data to your test database
```text
php bin/console doctrine:fixtures:load --env=test
```

### Run tests
On your terminal
```text
php bin/phpunit
```