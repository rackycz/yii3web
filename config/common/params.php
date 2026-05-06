<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Db\Mysql\Dsn;
use Yiisoft\Definitions\Reference;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\CsrfViewInjection;

return [
    'application' => require __DIR__ . '/application.php',

    'yiisoft/aliases' => [
        'aliases' => require __DIR__ . '/aliases.php',
    ],

    'yiisoft/view' => [
        'basePath' => null,
        'parameters' => [
            'assetManager' => Reference::to(AssetManager::class),
            'applicationParams' => Reference::to(ApplicationParams::class),
            'aliases' => Reference::to(Aliases::class),
            'urlGenerator' => Reference::to(UrlGeneratorInterface::class),
            'currentRoute' => Reference::to(CurrentRoute::class),
        ],
    ],

    'yiisoft/yii-view-renderer' => [
        'viewPath' => '@src',
        'layout' => '@src/Web/Shared/Layout/Main/layout.php',
        'injections' => [
            Reference::to(CsrfViewInjection::class),
        ],
    ],

    'yiisoft/db-mysql' => [
        'dsn' => (new Dsn('mysql', getenv('DB_HOST'), getenv('DB_DATABASE'), '3306', ['charset' => 'utf8mb4'])),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
    ],

    'yiisoft/yii-cycle' => [
        'dbal' => [
            'query-logger' => null,
            'default' => 'default',
            'aliases' => [],
            'databases' => [
                'default' => ['connection' => 'mysql']
            ],
            'connections' => [
                'mysql' => new \Cycle\Database\Config\MySQLDriverConfig(
                    connection: new \Cycle\Database\Config\MySQL\DsnConnectionConfig(
                        dsn: 'mysql:host=' . getenv('DB_HOST') . ';port=3306;charset=utf8mb4',
                        user: getenv('DB_USER'),
                        password: getenv('DB_PASSWORD'),
                    )
                ),
            ],
        ],

        'migrations' => [
            'directory' => '@root/migrations',
            'namespace' => 'App\\Migration',
            'table' => 'migration',
            'safe' => false,
        ],

        'schema-providers' => [
            \Cycle\Schema\Provider\SimpleCacheSchemaProvider::class => [
                'class' => \Cycle\Schema\Provider\SimpleCacheSchemaProvider::class,
                '__construct()' => [
                    'key' => 'cycle-schema-cache'
                ]
            ],
            \Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider::class => [
                'class' => \Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider::class,
            ],
        ],

        'entity-paths' => [
            '@src/Entity'
        ],
    ],
    'yiisoft/widget' => [
        'defaultTheme' => 'bootstrap5',
        'themes' => [
            'bootstrap5' => require __DIR__ . '/../../vendor/yiisoft/yii-dataview/config/widgets-themes.php',
        ],
        'theme' => 'bootstrap5',
    ],
];
