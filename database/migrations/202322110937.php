<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {
    public $name = 'Posts';
    public function up(Schema $schema)
    {

        $table = $schema->createTable('posts');
        $table->addColumn('id', Types::INTEGER, [
            'unsigned' => true,
            'autoincrement' => true
        ]);
        $table->addColumn('title', Types::STRING);
        $table->addColumn('body', Types::TEXT);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
            'default' => 'CURRENT_TIMESTAMP'
        ]);

        $table->setPrimaryKey(['id']);

    }

    public function down()
    {
        echo get_class($this). 'method down'. PHP_EOL;
    }
};
