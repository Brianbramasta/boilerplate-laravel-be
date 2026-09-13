<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class QueryFilterHelper
{
    // search global dan sorting
    public static function applyDynamicFilters(
        Builder $query,
        array $params,
        array $searchableColumns = []
    ): Builder {

        // --------- 1. GLOBAL SEARCH (LIKE) ----------
        $search = $params['search'] ?? null;

        if ($search && ! empty($searchableColumns)) {

            $query->where(function ($q) use ($searchableColumns, $search, $query) {
                foreach ($searchableColumns as $i => $column) {

                    // prefix kolom secara dinamis
                    $prefixed = self::resolveColumnPrefix($query, $column);

                    if ($i === 0) {
                        $q->where($prefixed, 'LIKE', "%{$search}%");
                    } else {
                        $q->orWhere($prefixed, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        // --------- 2. RANGE FILTER & IN FILTER ----------
        $filterColumn = $params['filter_column'] ?? [];
        // var_dump($filterColumn);
        if (! empty($filterColumn)) {
            foreach ($filterColumn as $column => $filters) {
                if (is_array($filters)) {
                    $prefixed = self::resolveColumnPrefix($query, $column);

                    // Check for IN filter
                    if (isset($filters['in'])) {
                        $inValues = $filters['in'];
                        if (is_array($inValues) && ! empty($inValues)) {
                            // Detect jika kolom adalah date field & values adalah month (1-12)
                            $isMonthFilter = self::isDateColumn($prefixed) &&
                                self::isMonthValues($inValues);

                            if ($isMonthFilter) {
                                // Filter by MONTH untuk date columns
                                $validMonths = array_filter($inValues, fn ($m) => is_numeric($m) && $m >= 1 && $m <= 12);

                                if (! empty($validMonths)) {
                                    $query->where(function ($q) use ($prefixed, $validMonths) {
                                        foreach ($validMonths as $month) {
                                            $q->orWhereMonth($prefixed, (int) $month);
                                        }
                                    });
                                }
                            } else {
                                // Regular IN filter
                                $query->whereIn($prefixed, $inValues);
                            }
                        }
                    }
                    // Check for range filter
                    elseif (isset($filters['range'])) {
                        $range = $filters['range'];

                        if (is_array($range) && count($range) >= 2) {
                            $query->whereBetween($prefixed, [$range[0], $range[1]]);
                        } else {
                            $query->where($prefixed, '=', $range[0]);
                        }
                    }
                }
            }
        }

        // --------- 2.1 STRICT FILTER (EXACT MATCH MULTI COLUMN) ----------
        $strict = $params['filter'] ?? [];

        if (! empty($strict) && ! empty($searchableColumns)) {

            $strictValues = array_values(
                array_filter($strict, fn ($v) => $v !== null && $v !== '')
            );

            if (! empty($strictValues)) {

                // 👉 kalau hanya 1 value
                if (count($strictValues) === 1) {

                    $value = $strictValues[0];

                    $query->where(function ($q) use ($value, $searchableColumns, $query) {

                        foreach ($searchableColumns as $index => $column) {
                            $prefixed = self::resolveColumnPrefix($query, $column);

                            $sql = "CAST($prefixed AS CHAR) = ?";

                            if ($index === 0) {
                                $q->whereRaw($sql, [$value]);
                            } else {
                                $q->orWhereRaw($sql, [$value]);
                            }
                        }
                    });
                } else {

                    // 👉 lebih dari 1 value → AND antar group
                    $query->where(function ($q) use ($strictValues, $searchableColumns, $query) {

                        foreach ($strictValues as $value) {

                            $q->where(function ($subQ) use ($value, $searchableColumns, $query) {

                                foreach ($searchableColumns as $index => $column) {
                                    $prefixed = self::resolveColumnPrefix($query, $column);

                                    $sql = "CAST($prefixed AS CHAR) = ?";

                                    if ($index === 0) {
                                        $subQ->whereRaw($sql, [$value]);
                                    } else {
                                        $subQ->orWhereRaw($sql, [$value]);
                                    }
                                }
                            });
                        }
                    });
                }
            }
        }

        // --------- 3. SORTING ----------
        $sortColumn = $params['column'] ?? 'created_at';
        $sortDirection = $params['sort'] ?? 'asc';

        // validasi asc/desc
        $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc'])
            ? strtolower($sortDirection)
            : 'desc';

        // prefix kolom sorting
        $sortColumn = self::resolveColumnPrefix($query, $sortColumn);

        $query->orderBy($sortColumn, $sortDirection);

        return $query;
    }

    public static function strictFilterCollection(
        Collection $collection,
        array $filters,
        array $searchableColumns
    ): Collection {
        if (empty($filters)) {
            return $collection;
        }

        return $collection->filter(function ($item) use ($filters, $searchableColumns) {
            foreach ($filters as $value) {
                foreach ($searchableColumns as $column) {
                    // Extract column name tanpa prefix (generik untuk semua tabel)
                    $columnName = self::extractColumnName($column);

                    $itemValue = (string) ($item->{$columnName} ?? '');

                    if ($itemValue === $value) {
                        return true;
                    }
                }
            }

            return false;
        })->values();
    }

    private static function extractColumnName(string $column): string
    {
        // Extract column name dari prefixed (e.g., "mt_employees.birth_date" -> "birth_date")
        // return explode('.', $prefixed)[1] ?? $prefixed;
        // if (str_contains($column, '.')) {
        //     return explode('.', $column)[1] ?? $column;
        // }

        // return $column;

        if (str_contains($column, '.')) {
            return array_slice(explode('.', $column), -1)[0];
        }

        return $column;
    }

    private static function resolveColumnPrefix(Builder $query, string $column): string
    {
        // kalau sudah ada prefix, return langsung
        if (str_contains($column, '.')) {
            return $column;
        }

        // jika prefix - maka dikembalikan sesuai select
        if (str_contains($column, '-')) {
            $column = str_replace('-', '', $column);

            return $column;
        }

        // tabel utama
        $baseTable = $query->getQuery()->from;

        // cek kolom di tabel utama
        if (Schema::hasColumn($baseTable, $column)) {
            return $baseTable.'.'.$column;
        }

        // cek kolom di tabel join
        $joins = $query->getQuery()->joins ?? [];

        foreach ($joins as $join) {
            $table = $join->table;

            // joinSub menghasilkan Expression; skip karena bukan tabel fisik
            if (! is_string($table)) {
                continue;
            }

            // handle alias: "table as t"
            $tableName = preg_split('/\s+as\s+/i', $table)[0] ?? $table;

            if (Schema::hasColumn($tableName, $column)) {
                return $tableName.'.'.$column;
            }
        }

        // fallback: tabel utama
        return $baseTable.'.'.$column;
    }

    /**
     * Check if column is a date/datetime column
     */
    private static function isDateColumn(string $prefixed): bool
    {
        // List of common date column names
        $dateColumns = [
            'birth_date',
            'created_at',
            'updated_at',
            'deleted_at',
            'start_date',
            'end_date',
            'date_of_birth',
        ];

        // Extract column name dari prefixed (e.g., "mt_employees.birth_date" -> "birth_date")
        $columnName = explode('.', $prefixed)[1] ?? $prefixed;

        return in_array($columnName, $dateColumns);
    }

    /**
     * Check if values look like month numbers (1-12)
     */
    private static function isMonthValues(array $values): bool
    {
        foreach ($values as $v) {
            if (! is_numeric($v) || $v < 1 || $v > 12) {
                return false;
            }
        }

        return true;
    }

    /**
     * Fungsi untuk melakukan join antar dua model dan menambahkan select kolom dari left model.
     * $dataJoin = [
     *    'query' => ... (Builder Instance),
     *    'fromModel' => ... (string|model),
     *    'fromModelId' => ... (string),
     *    'leftModel' => ... (string|model),
     *    'leftModelId' => ... (string),
     *    'addSelect' => ... (array|string) Kolom atau array kolom yang ingin di-select dari leftModel,
     *    'joinType' => ... (string, optional, default 'left'), // left, inner, right dll.
     *    'where' => ... (array, optional) // contoh: [['column', 'operator', 'value'], ...]
     * ]
     */
    public static function addJoinModel($dataJoin)
    {
        // Koreksi dan tambahkan prefix connection
        $query = $dataJoin['query'] ?? null;
        $fromModel = $dataJoin['fromModel'] ?? null;
        $fromModelId = $dataJoin['fromModelOn'] ?? 'id';
        $leftModel = $dataJoin['leftModel'] ?? null;
        $leftModelId = $dataJoin['leftModelOn'] ?? null;
        $addSelect = $dataJoin['addSelect'] ?? [];
        $joinType = strtolower($dataJoin['joinType'] ?? 'left'); // Bisa: left, inner, right, cross, dll
        $joinWhere = $dataJoin['where'] ?? []; // opsional, array of array (kondisi tambahan dalam join)

        // Validasi wajib field
        if (empty($query) || empty($fromModel) || empty($fromModelId) || empty($leftModel) || empty($leftModelId)) {
            throw new \InvalidArgumentException('Parameter dataJoin tidak lengkap atau ada yang null.');
        }

        // Resolve instance dari fromModel dan leftModel
        $fromModelInstance = is_object($fromModel)
            ? $fromModel
            : (is_string($fromModel) ? app($fromModel) : null);

        $leftModelInstance = is_object($leftModel)
            ? $leftModel
            : (is_string($leftModel) ? app($leftModel) : null);

        if (! $fromModelInstance || ! method_exists($fromModelInstance, 'getTable')) {
            throw new \InvalidArgumentException('fromModel tidak valid.');
        }
        if (! $leftModelInstance || ! method_exists($leftModelInstance, 'getTable')) {
            throw new \InvalidArgumentException('leftModel tidak valid.');
        }

        $fromModelTable = $fromModelInstance->getTable();
        $leftModelTable = $leftModelInstance->getTable();

        // Ambil database name dari connection, bukan connection name
        $fromModelDatabase = null;
        $leftModelDatabase = null;

        if (method_exists($fromModelInstance, 'getConnection')) {
            $fromDbConfig = $fromModelInstance->getConnection()->getConfig();
            $fromModelDatabase = $fromDbConfig['database'] ?? null;
        }

        if (method_exists($leftModelInstance, 'getConnection')) {
            $leftDbConfig = $leftModelInstance->getConnection()->getConfig();
            $leftModelDatabase = $leftDbConfig['database'] ?? null;
        }

        // Dapatkan connection name untuk kedua model (masih dipakai untuk switching koneksi)
        $fromModelConnection = method_exists($fromModelInstance, 'getConnectionName')
            ? $fromModelInstance->getConnectionName()
            : null;
        $leftModelConnection = method_exists($leftModelInstance, 'getConnectionName')
            ? $leftModelInstance->getConnectionName()
            : null;

        // Switching koneksi jika diperlukan (opsional)
        if (
            $leftModelConnection &&
            method_exists($query, 'getConnection') &&
            $query->getConnection()->getName() !== $leftModelConnection
        ) {
            $query->connection = $leftModelInstance->getConnection();
        }

        // Jika beda koneksi/database, tambahkan prefix database di join
        $leftTableWithPrefix =
            $leftModelDatabase ? ($leftModelDatabase.'.'.$leftModelTable) : $leftModelTable;
        $fromTableWithPrefix =
            $fromModelDatabase ? ($fromModelDatabase.'.'.$fromModelTable) : $fromModelTable;

        // Map joinType ke metode Query Builder Laravel
        $joinFunction = match ($joinType) {
            'left', 'leftjoin' => 'leftJoin',
            'inner', 'join' => 'join',
            'right', 'rightjoin' => 'rightJoin',
            'cross', 'crossjoin' => 'crossJoin',
            default => 'leftJoin'
        };

        // Lakukan join, perhatikan prefix database dan hanya tambahkan filter deleted_at jika kolom tersebut ada di tabel
        $query->{$joinFunction}(
            $leftTableWithPrefix,
            function ($join) use (
                $fromTableWithPrefix,
                $fromModelId,
                $leftTableWithPrefix,
                $leftModelId,
                $leftModelInstance,
                $joinFunction,
                $joinWhere
            ) {
                $join->on(
                    $fromTableWithPrefix.'.'.$fromModelId,
                    '=',
                    $leftTableWithPrefix.'.'.$leftModelId
                );
                // Tambahkan where tambahan (array of array) ke dalam join
                if (! empty($joinWhere) && is_array($joinWhere)) {
                    foreach ($joinWhere as $cond) {
                        // $cond: ['column', 'operator', 'value'] atau ['column', 'value'] (operator default '=')
                        if (is_array($cond) && count($cond) >= 2) {
                            $column = $cond[0];
                            $operator = (isset($cond[2]) ? $cond[1] : '=');
                            $value = (isset($cond[2]) ? $cond[2] : $cond[1]);
                            // Jika column tidak mengandung dot, default ke left table
                            if (strpos($column, '.') === false) {
                                $column = $leftTableWithPrefix.'.'.$column;
                            }
                            $join->where($column, $operator, $value);
                        }
                    }
                }
                // Cek jika kolom deleted_at ada, baru filter whereNull
                // Bila joinType left, baru filter deleted_at (soal keamanan softdeletes relasi)
                if (
                    in_array('deleted_at', $leftModelInstance->getConnection()
                        ->getSchemaBuilder()
                        ->getColumnListing($leftModelInstance->getTable())) &&
                    in_array($joinFunction, ['leftJoin', 'rightJoin']) // hanya filter di join type tertentu (umumnya left dan right)
                ) {
                    $join->whereNull($leftTableWithPrefix.'.deleted_at');
                }
            }
        );

        // addSelect: string satuan atau array kolom, tambahkan prefix database dan join table
        if (! empty($addSelect)) {
            $columns = is_array($addSelect) ? $addSelect : [$addSelect];
            $databasePrefix = $leftModelDatabase ? $leftModelDatabase.'.' : '';

            foreach ($columns as $column) {
                // Cek jika ada " as ", berarti ingin alias custom, contoh: "id as component_field_id"
                $columnParts = preg_split('/\s+as\s+/i', $column);
                if (count($columnParts) == 2) {
                    $baseColumn = trim($columnParts[0]);
                    $alias = trim($columnParts[1]);
                } else {
                    $baseColumn = trim($column);
                    $alias = trim($column);
                }

                // Hindari select duplikat atau kolom kosong
                $selectName = $databasePrefix.$leftModelTable.'.'.$baseColumn;
                $currentColumns = $query->getQuery()->columns ?? [];
                $columnAlreadySelected = false;
                foreach ($currentColumns as $selected) {
                    // Cek berbagai kemungkinan style select
                    if (
                        $selected === $alias ||                                         // select sebagai nama biasa
                        $selected === $selectName ||                                    // select dengan prefix table
                        // Pastikan untuk kasus as...
                        stripos($selected, $selectName.' as '.$alias) !== false     // select dengan alias
                    ) {
                        $columnAlreadySelected = true;
                        break;
                    }
                }
                if ($baseColumn && ! $columnAlreadySelected) {
                    $query->addSelect($selectName.' as '.$alias);
                }
            }
        }

        // $rawSql = $query->toSql();
        // $bindings = $query->getBindings();
        // var_dump(['sql' => $rawSql, 'bindings' => $bindings]);

        return $query;
    }
}
