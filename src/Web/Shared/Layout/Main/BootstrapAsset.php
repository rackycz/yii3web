<?php

declare(strict_types=1);

namespace App\Web\Shared\Layout\Main;

use Yiisoft\Assets\AssetBundle;

final class BootstrapAsset extends AssetBundle
{
    public ?string $basePath = '@assets/main';
    public ?string $baseUrl = '@assetsUrl/main';
    public ?string $sourcePath = '@vendor/twbs/bootstrap/dist';

    public array $css = [
        'css/bootstrap.min.css',
    ];

    public array $js = [
        'js/bootstrap.bundle.min.js',
    ];

    public array $depends = [
        // Add any dependencies here if needed
    ];
}
