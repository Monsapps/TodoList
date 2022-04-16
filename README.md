# ToDoList
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/0705cbd0e97342418b53262cbf996320)](https://www.codacy.com/gh/Monsapps/TodoList/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Monsapps/TodoList&amp;utm_campaign=Badge_Grade)

## Installation

### Installation requirements
*   PHP (>8.0)
*   MySQL (>5.7)
*   Apache (>2.4)
*   Symfony bundle (6.0)
*   Composer (>2.2)

### First step : install project dependencies
In your installation directory open terminal and type
```text
composer install
```

### Second step : Create .env.local file
*   Update .env.local file with your database server info
```text
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```

### Third step : database
On your terminal
*   Create your database (optionnal)
```text
php bin/console doctrine:database:create
```
*   Create all tables
```text
php bin/console doctrine:migrations:migrate
```

## Resources
*   [saro0h / projet8-TodoList](https://github.com/saro0h/projet8-TodoList)
*   [Contributing guide](Resources/doc/CONTRIBUTING.md)
