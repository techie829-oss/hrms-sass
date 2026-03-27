<?php

namespace App\Core\Traits;

trait UsesPublicSchema
{
    /**
     * Get the table associated with the model, 
     * prefixed with the 'public.' schema for PostgreSQL.
     */
    public function getTable()
    {
        $table = parent::getTable();
        
        // Return qualified table name if not already qualified
        return str_contains($table, '.') ? $table : "public.{$table}";
    }

    /**
     * Qualify the given column name by the model's table.
     * Overridden to handle already qualified table names.
     */
    public function qualifyColumn($column)
    {
        if (str_contains($column, '.')) {
            return $column;
        }

        return $this->getTable() . '.' . $column;
    }
}
