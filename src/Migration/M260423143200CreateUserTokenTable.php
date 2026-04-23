<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Expression\Expression;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;

/**
 * Class M251013160000CreateUserTokenTable
 */
final class M260423143200CreateUserTokenTable implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $columnBuilder = $b->columnBuilder();

        $b->createTable('user_token', [
            'id' => $columnBuilder::primaryKey(),
            'id_user' => $columnBuilder::integer()->notNull(),
            'id_token_type' => $columnBuilder::integer()->notNull(),
            'token' => $columnBuilder::string()->notNull(),
            'expires_at' => $columnBuilder::datetime(),
            'created_by' => $columnBuilder::integer(),
            'updated_by' => $columnBuilder::integer(),
            'deleted_by' => $columnBuilder::integer(),
            'created_at' => $columnBuilder::datetime()->notNull()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
            'updated_at' => $columnBuilder::dateTime(),
            'deleted_at' => $columnBuilder::dateTime(),
        ]);

        $b->addForeignKey('user_token', 'fk_usertoken_type', 'id_token_type', 'user_token_type', 'id');
        $b->addForeignKey('user_token', 'fk_usertoken_created_by', 'created_by', 'user', 'id');
        $b->addForeignKey('user_token', 'fk_usertoken_updated_by', 'updated_by', 'user', 'id');
        $b->addForeignKey('user_token', 'fk_usertoken_deleted_by', 'deleted_by', 'user', 'id');
        $b->addForeignKey('user_token', 'fk_usertoken_user', 'id_user', 'user', 'id');
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('user_token');
    }
}
