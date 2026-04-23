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
final class M260423143100CreateUserTokenTypeTable implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $columnBuilder = $b->columnBuilder();

        $b->createTable('user_token_type', [
            'id' => $columnBuilder::primaryKey(),
            'name' => $columnBuilder::string()->notNull()->unique(),
            'created_by' => $columnBuilder::integer(),
            'updated_by' => $columnBuilder::integer(),
            'deleted_by' => $columnBuilder::integer(),
            'created_at' => $columnBuilder::datetime()->notNull()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
            'updated_at' => $columnBuilder::dateTime(),
            'deleted_at' => $columnBuilder::dateTime(),
        ]);


    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('user_token_type');
    }
}
