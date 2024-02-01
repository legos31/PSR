<?php


namespace Framework\console\commands;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;


class MigrateCommand implements \Framework\console\CommandInterface
{
    private string $name = 'migrate';
    private const MIGRATIONS_TABLE = 'migrations';

    public function __construct(private Connection $connection)
    {
    }

    public function execute(array $params = []): int
    {
        try {
            //$this->connection->setAutoCommit(false);
            $this->createMigrationsTable();
            $this->connection->beginTransaction();
            $appliedMigrations = $this->getAppliedMigrations();
            $migrationFiles = $this->getMigrationFiles();
            $migrationsToApple = array_values(array_diff($migrationFiles, $appliedMigrations));

            $schema = new Schema ();

            foreach ($migrationsToApple as $migration) {
                $migrationInstance = require (BASE_PATH . '/database/migrations/'. $migration);
                $migrationInstance->up($schema);
                $this->addMigration($migration);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
                echo 'Table created successfully!'.PHP_EOL;
            }

            //$this->connection->commit();
        } catch (\Throwable $e) {
            //$this->connection->rollBack();
            throw $e;
        };
        $this->connection->setAutoCommit(true);
        return 0;
    }

    private function createMigrationsTable()
    {
        $schemaManager = $this->connection->createSchemaManager();
        if (!$schemaManager->tablesExist(self::MIGRATIONS_TABLE)) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATIONS_TABLE);
            $table->addColumn('id', Types::INTEGER, [
                'unsigned' => true,
                'autoincrement' => true
            ]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP'
            ]);

            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
            $this->connection->executeQuery($sqlArray[0]);
            echo 'Migrations table created successfully!'.PHP_EOL;
        } else {
            echo 'Table ' . self::MIGRATIONS_TABLE . ' is exist!';
        }


    }

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        return $queryBuilder->select('migration')->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles(): array
    {
        $migrationFiles = scandir(BASE_PATH . '/database/migrations');
        $filteredFiles =  array_filter($migrationFiles, function ($fileName) {
           return !in_array($fileName, ['.', '..']);
        });
        return array_values($filteredFiles);
    }

    private function addMigration(string $migration)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert(self::MIGRATIONS_TABLE)
            ->values(['migration' => ':migration'])
            ->setParameter('migration' , $migration)
            ->executeQuery();
    }
}