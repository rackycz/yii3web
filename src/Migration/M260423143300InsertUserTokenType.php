<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;

/**
 * Insert user token types into user_token_type table
 */
final class M260423143300InsertUserTokenType implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $b->batchInsert('user_token_type', ['id', 'name'], [
            [1, 'WEB_PASSWORD_HASH'],
            [2, 'WEB_PASSWORD_DEFAULT'],
            [3, 'API_PASSWORD_HASH'],
            [4, 'API_PASSWORD_DEFAULT'],
            [5, 'EMAIL_VERIFY'],
        ]);
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->delete('user_token_type');
    }
}
