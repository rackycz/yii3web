<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Expression\Expression;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;
use Yiisoft\Security\PasswordHasher;

/**
 * Insert user token types into user_token_type table
 */
final class M260423143400InsertUser implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $now = new Expression('CURRENT_TIMESTAMP');
        $b->batchInsert('user', ['id', 'name', 'surname', 'username', 'phone', 'email', 'email_verified_at', 'status'], [
            [1, 'Admin', 'Admin', 'admin', '+420123456789', 'admin@web.cz', $now, 100],
        ]);

        $webPassword = 'admin';
        $apiPassword = 'admin';
        $webPasswordHash = (new PasswordHasher())->hash($webPassword);
        $apiPasswordHash = (new PasswordHasher())->hash($apiPassword);
        $b->batchInsert('user_token', ['id', 'id_user', 'id_token_type', 'expires_at', 'token'], [
            [1, 1, 1, null, $webPasswordHash],
            [2, 1, 2, null, $webPassword],
            [3, 1, 3, null, $apiPasswordHash],
            [4, 1, 4, null, $apiPassword],
        ]);
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->delete('user');
        $b->delete('user_token');
    }
}
