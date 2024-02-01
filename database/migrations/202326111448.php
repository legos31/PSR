<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {
    public $name = 'Authors';
    public function up(Schema $schema)
    {

        $table = $schema->createTable('authors');
        $table->addColumn('id', Types::INTEGER, [
            'unsigned' => true,
            'autoincrement' => true
        ]);
        $table->addColumn('name', Types::STRING);
        $table->addColumn('description', Types::TEXT);
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
