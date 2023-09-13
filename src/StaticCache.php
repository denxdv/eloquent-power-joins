<?php

namespace Kirschbaum\PowerJoins;

use Illuminate\Database\Eloquent\Model;

class StaticCache
{
    /**
     * Cache to not join the same relationship twice.
     * @var array<int, string>
     */
    public static array $powerJoinAliasesCache = [];

    public static function getTableOrAliasForModel(Model $model): string
    {
        if (self::isPowerJoinsAliasSet($model) && isset(static::$powerJoinAliasesCache[$model->powerJoinsAlias])) {
            return static::$powerJoinAliasesCache[$model->powerJoinsAlias];
        } else {
            return $model->getTable();
        }
    }

    public static function setTableAliasForModel(Model $model, $alias): void
    {
        if ( ! self::isPowerJoinsAliasSet($model)) {
            $model->powerJoinsAlias = uniqid();
        }
        static::$powerJoinAliasesCache[$model->powerJoinsAlias] = $alias;
    }

    protected static function isPowerJoinsAliasSet(Model $model)
    {
        return $model->powerJoinsAlias ?? false;
    }

    public static function clear(): void
    {
        static::$powerJoinAliasesCache = [];
    }
}
