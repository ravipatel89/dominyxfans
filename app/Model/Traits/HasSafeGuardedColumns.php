<?php

namespace App\Model\Traits;

/**
 * Laravel's default guarded-attribute check (GuardsAttributes::isGuardableColumn)
 * queries information_schema.columns via the schema builder, which always selects
 * generation_expression. MariaDB < 10.2 and MySQL < 5.7.6 don't expose that column,
 * so any model using $guarded (instead of $fillable or $guarded = []) fails on first
 * mass assignment. Mirror the trimmed information_schema query already used in
 * AttachmentPlacementMigration instead of the framework's native introspection.
 */
trait HasSafeGuardedColumns
{
    protected function isGuardableColumn($key)
    {
        if ($this->hasSetMutator($key) || $this->hasAttributeSetMutator($key) || $this->isClassCastable($key)) {
            return true;
        }

        if (!isset(static::$guardableColumns[get_class($this)])) {
            $connection = $this->getConnection();

            if ($connection->getDriverName() === 'sqlite') {
                $columns = $connection->getSchemaBuilder()->getColumnListing($this->getTable());
            } else {
                $table = $connection->getTablePrefix().$this->getTable();
                $query = $connection->table('information_schema.columns')
                    ->where(function ($q) use ($table) {
                        $q->where('table_name', $table)
                            ->orWhere('table_name', $this->getTable());
                    });
                $connection->getDriverName() === 'pgsql'
                    ? $query->whereRaw('table_schema = current_schema()')
                    : $query->whereRaw('table_schema = DATABASE()');

                $columns = array_map(function ($row) {
                    $row = array_change_key_case((array) $row, CASE_LOWER);
                    return $row['column_name'] ?? null;
                }, $query->get(['column_name'])->all());

                $columns = array_map('strtolower', array_values(array_filter($columns)));
            }

            if (empty($columns)) {
                return true;
            }

            static::$guardableColumns[get_class($this)] = $columns;
        }

        return in_array(strtolower((string) $key), static::$guardableColumns[get_class($this)], true);
    }
}
