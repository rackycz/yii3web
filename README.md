<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px" alt="Yii">
    </a>
    <h1 align="center">Yii3 web application</h1>
    <h3 align="center">A CUSTOMIZED application template for a new web project</h3>
    <br>
</p>

> This application is a clone of the official web application https://github.com/yiisoft/app
>
> ... and is discussed in my wiki page
[Yii3 - How to start](https://www.yiiframework.com/wiki/2581/yii3-how-to-start)



What was added/modififed (for details see the Git history):

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
    - CORS enabled
- REST API endpoints added
    - / = Lists all the users
    - /login = validates the login and creates the bearer token
        - Use this data in the POST request: { "username": "admin", "password": "admin" }
    - /bearer = tests if the correct bearer token is in the POST request
        - Use the "Bearer Token" Auth Type in Postman on the Authorization tab.
- Enabled step debugging via xDebug
- User-CRUD implemented + CSRF
- Font Awesome added (via CDN)
- GridView used to display list of users. (pagesize = 1)
- Both technologies `ActiveRecord` and `QueryBuilder + Repository` are used when updating user for comparison.

TODO:

- Filter in the GridView should be an array: `<input name="filter[id]" ...>`. But it may not be possible.
  See renderFilter() in DataColumnRenderer.
- Study all the files in the `src/Api/Shared` folder. Are they needed? I just copied them from the API demo.
- Show usage of https://htmx.org/
- Add language and timezone selection to the main menu, save them to the user profile
- Place the language-code at the beginning of the URL?
- Implement translations and localization (datetime format, numbers, etc.)
