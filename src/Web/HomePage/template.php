<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use Yiisoft\View\WebView;

/**
 * @var WebView $this
 * @var ApplicationParams $applicationParams
 * @var array $users
 */

$this->setTitle($applicationParams->name);
?>

<div class="text-center">
    <h1>Hello!</h1>

    <p>Let's start something great with <strong>Yii3</strong>!</p>

    <p>
        <a href="https://yiisoft.github.io/docs/" target="_blank" rel="noopener">
            <i>Don't forget to check the guide.</i>
        </a>
    </p>

    <p>
        Number of users in the database: <?= count($users) ?>
    </p>
</div>
