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
 * Handles the creation of table `user`.
 */
final class M260423143000CreateUserTable implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $columnBuilder = $b->columnBuilder();

        $b->createTable('user', [
            'id' => $columnBuilder::primaryKey(),
            'name' => $columnBuilder::string()->notNull(),
            'surname' => $columnBuilder::string()->notNull(),
            'username' => $columnBuilder::string(),
            'phone' => $columnBuilder::string(),
            'email' => $columnBuilder::string()->notNull()->unique(),
            'email_verified_at' => $columnBuilder::datetime(),
            'status' => $columnBuilder::integer()->notNull()->defaultValue(100),
            'created_by' => $columnBuilder::integer(),
            'updated_by' => $columnBuilder::integer(),
            'deleted_by' => $columnBuilder::integer(),
            'created_at' => $columnBuilder::datetime()->notNull()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
            'updated_at' => $columnBuilder::datetime(),
            'deleted_at' => $columnBuilder::datetime(),
        ]);

        $b->addForeignKey('user', 'fk_user_created_by', 'created_by', 'user', 'id');
        $b->addForeignKey('user', 'fk_user_updated_by', 'updated_by', 'user', 'id');
        $b->addForeignKey('user', 'fk_user_deleted_by', 'deleted_by', 'user', 'id');
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('user');
    }
}
