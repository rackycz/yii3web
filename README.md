<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px" alt="Yii">
    </a>
    <h1 align="center">Yii3 web application</h1>
    <h3 align="center">A CUSTOMIZED application template for a new web project</h3>
    <br>
</p>

This is a clone of the official web application https://github.com/yiisoft/app

What was added/modififed:

- CSS Bootstrap was added and the main menu was created
    - Rendered using `Yiisoft\Yii\Widgets\Menu` from https://github.com/yiisoft/yii-widgets
- Added more containers (mainly MariaDB) to `docker/dev/compose.yml`
    - Added more env variables to `docker/.env`
- MariaDB is used, migrations were enabled in CLI, password hashing, Annotations and Repository were engaged.
- REST API enabled. See `config/common/routes.php` and `src/Api`.
    - Some middleware was moved from `config/web/di/application.php` to `config/common/routes.php`
    - ... because session and CSRF are only needed in the web application
    - Note: Error "422 Unprocessable entity" = missing CSRF token
    - Note: Error "Formatter is not set" = the API does not know whether XML or JSON should be returned. See the
      solution in `config/common/routes.php`.
- REST API endpoints added
    - / = Lists all the users
    - /login = validates the login and creates the bearer token
        - Use this data in the POST request: { "username": "admin", "password": "admin" }
    - /bearer = tests if the correct bearer token is in the POST request
        - Use the "Bearer Token" Auth Type in Postman on the Authorization tab.

TODO:

- Study all the files in the `src/Api/Shared` folder. Are they needed? I just copied them from the API demo.
- Use ActiveRecord
- Enable Debugging via xDebug
- Show usage of https://htmx.org/
- Implement user management
- Add language and timezone selection to the main menu, save them to the user profile
- Place the language-code at the beginning of the URL?
- Implement translations and localization (datetime format, numbers, etc.)
