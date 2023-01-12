<?php

declare(strict_types=1);

namespace Yiisoft\Db\Schema;

use Throwable;
use Yiisoft\Db\Constraint\ConstraintSchemaInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;

/**
 * The SchemaInterface class that represents the schema for a database table. It provides a set of methods for working
 * with the schema of a database table, such as accessing the columns, indexes, and constraints of a table, as well as
 * methods for creating, dropping, and altering tables.
 */
interface SchemaInterface extends ConstraintSchemaInterface
{
    public const SCHEMA = 'schema';
    public const PRIMARY_KEY = 'primaryKey';
    public const INDEXES = 'indexes';
    public const CHECKS = 'checks';
    public const FOREIGN_KEYS = 'foreignKeys';
    public const DEFAULT_VALUES = 'defaultValues';
    public const UNIQUES = 'uniques';

    public const TYPE_PK = 'pk';
    public const TYPE_UPK = 'upk';
    public const TYPE_BIGPK = 'bigpk';
    public const TYPE_UBIGPK = 'ubigpk';
    public const TYPE_CHAR = 'char';
    public const TYPE_STRING = 'string';
    public const TYPE_TEXT = 'text';
    public const TYPE_TINYINT = 'tinyint';
    public const TYPE_SMALLINT = 'smallint';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_BIGINT = 'bigint';
    public const TYPE_FLOAT = 'float';
    public const TYPE_DOUBLE = 'double';
    public const TYPE_DECIMAL = 'decimal';
    public const TYPE_DATETIME = 'datetime';
    public const TYPE_TIMESTAMP = 'timestamp';
    public const TYPE_TIME = 'time';
    public const TYPE_DATE = 'date';
    public const TYPE_BINARY = 'binary';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_MONEY = 'money';
    public const TYPE_JSON = 'json';

    public const PHP_TYPE_INTEGER = 'integer';
    public const PHP_TYPE_STRING = 'string';
    public const PHP_TYPE_BOOLEAN = 'boolean';
    public const PHP_TYPE_DOUBLE = 'double';
    public const PHP_TYPE_RESOURCE = 'resource';
    public const PHP_TYPE_ARRAY = 'array';
    public const PHP_TYPE_NULL = 'NULL';

    /**
     * @return string|null The default schema name.
     */
    public function getDefaultSchema(): string|null;

    /**
     * Determines the PDO type for the given PHP data value.
     *
     * @param mixed $data The data whose PDO type is to be determined
     *
     * @return int The PDO type.
     *
     * @link http://www.php.net/manual/en/pdo.constants.php
     */
    public function getPdoType(mixed $data): int;

    /**
     * Returns the actual name of a given table name.
     *
     * This method will strip off curly brackets from the given table name and replace the percentage character '%' with
     * {@see ConnectionInterface::tablePrefix}.
     *
     * @param string $name The table name to be converted.
     *
     * @return string The real name of the given table name.
     */
    public function getRawTableName(string $name): string;

    /**
     * Returns all schema names in the database, except system schemas.
     *
     * @param bool $refresh Whether to fetch the latest available schema names. If this is false, schema names fetched
     * previously (if available) will be returned.
     *
     * @throws NotSupportedException
     *
     * @return array All schema names in the database, except system schemas.
     */
    public function getSchemaNames(bool $refresh = false): array;

    /**
     * Returns all table names in the database.
     *
     * @param string $schema The schema of the tables. Defaults to empty string, meaning the current or default schema
     * name.
     * If not empty, the returned table names will be prefixed with the schema name.
     * @param bool $refresh Whether to fetch the latest available table names. If this is false, table names fetched
     * previously (if available) will be returned.
     *
     * @throws NotSupportedException
     *
     * @return array All table names in the database.
     */
    public function getTableNames(string $schema = '', bool $refresh = false): array;

    /**
     * Create a column schema builder instance giving the type and value precision.
     *
     * This method may be overridden by child classes to create a DBMS-specific column schema builder.
     *
     * @param string $type the type of the column.
     * {@see AbstractColumnSchemaBuilder::$type} for supported types.
     * @param array|int|string|null $length The length or precision of the column.
     *
     * {@see ColumnSchemaBuilderInterface::$length}.
     *
     * @return ColumnSchemaBuilderInterface column schema builder instance
     *
     * @psalm-param string[]|int[]|int|string|null $length
     */
    public function createColumnSchemaBuilder(
        string $type,
        array|int|string $length = null
    ): ColumnSchemaBuilderInterface;

    /**
     * Returns all unique indexes for the given table.
     *
     * Each array element is of the following structure:
     *
     * ```php
     * [
     *     'IndexName1' => ['col1' [, ...]],
     *     'IndexName2' => ['col2' [, ...]],
     * ]
     * ```
     *
     * @param TableSchemaInterface $table The table metadata.
     *
     * @throws Exception
     * @throws InvalidConfigException
     * @throws Throwable
     *
     * @return array All unique indexes for the given table.
     */
    public function findUniqueIndexes(TableSchemaInterface $table): array;

    /**
     * Obtains the metadata for the named table.
     *
     * @param string $name Table name. The table name may contain schema name if any. Do not quote the table name.
     * @param bool $refresh Whether to reload the table schema even if it is found in the cache.
     *
     * @return TableSchemaInterface|null Table metadata. `null` if the named table does not exist.
     */
    public function getTableSchema(string $name, bool $refresh = false): TableSchemaInterface|null;

    /**
     * Returns the metadata for all tables in the database.
     *
     * @param string $schema The schema of the tables. Defaults to empty string, meaning the current or default schema
     * name.
     * @param bool $refresh Whether to fetch the latest available table schemas. If this is `false`, cached data may be
     * returned if available.
     *
     * @throws NotSupportedException
     *
     * @return array The metadata for all tables in the database. Each array element is an instance of
     * {@see TableSchemaInterface} or its child class.
     */
    public function getTableSchemas(string $schema = '', bool $refresh = false): array;

    /**
     * Returns a value indicating whether a SQL statement is for read purpose.
     *
     * @param string $sql The SQL statement.
     *
     * @return bool Whether a SQL statement is for read purpose.
     */
    public function isReadQuery(string $sql): bool;

    /**
     * Refreshes the schema.
     *
     * This method cleans up all cached table schemas so that they can be re-created later to reflect the database
     * schema change.
     */
    public function refresh(): void;

    /**
     * Refreshes the particular table schema.
     *
     * This method cleans up cached table schema so that it can be re-created later to reflect the database schema
     * change.
     *
     * @param string $name Table name.
     */
    public function refreshTableSchema(string $name): void;

    /**
     * Allows you to enable and disable the schema cache.
     *
     * @param bool $value Whether to enable or disable the schema cache.
     */
    public function schemaCacheEnable(bool $value): void;

    /**
     * Returns all view names in the database.
     *
     * @param string $schema The schema of the views. Defaults to empty string, meaning the current or default schema
     * name. If not empty, the returned view names will be prefixed with the schema name.
     * @param bool $refresh Whether to fetch the latest available view names. If this is false, view names fetched
     * previously (if available) will be returned.
     *
     * @return array All view names in the database.
     */
    public function getViewNames(string $schema = '', bool $refresh = false): array;
}
