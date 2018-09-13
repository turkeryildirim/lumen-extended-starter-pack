<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Closure;

/**
 * Class BaseModel
 *
 * @package App\Models
 */
abstract class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * @param array $columns
     * @param array $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAll(array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param array $columns
     * @param array $with
     * @return \Exception|mixed
     */
    public static function getFirst(array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->get($columns)->first();
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param array $columns
     * @param array $with
     * @return \Exception|mixed
     */
    public static function getLast(array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->get($columns)->last();
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $field
     * @param string $value
     * @param array  $columns
     * @param array  $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findBy(string $field, string $value, array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->where($field, '=', $value)->with($with)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param array $where
     * @param array $columns
     * @param array $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhere(array $where, array $columns = ['*'], array $with = [])
    {
        $model = self::applyConditions($where);
        try {
            return $model->with($with)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param array $where
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function applyConditions(array $where)
    {
        $model = self::query();
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $model = $model->where($field, $condition, $val);
            } else {
                $model = $model->where($field, $value);
            }
        }
        return $model;
    }

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     * @param array  $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhereIn(string $field, array $values, array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->whereIn($field, $values)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     * @param array  $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhereNotIn(string $field, array $values, array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->whereNotIn($field, $values)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     * @param array  $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhereBetween(string $field, array $values, array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->whereBetween($field, $values)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     * @param array  $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhereNotBetween(string $field, array $values, array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->whereNotBetween($field, $values)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $field
     * @param array  $columns
     * @param array  $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhereNull(string $field, array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->whereNull($field)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $field
     * @param array  $columns
     * @param array  $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhereNotNull(string $field, array $columns = ['*'], array $with = [])
    {
        try {
            return self::query()->with($with)->whereNotNull($field)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string        $relation
     * @param \Closure|null $closure
     * @param array         $columns
     * @param array         $with
     * @return \Exception|\Illuminate\Database\Eloquent\Collection
     */
    public static function findWhereHas(
        string $relation,
        Closure $closure = null,
        array $columns = ['*'],
        array $with = []
    ) {
        try {
            return self::query()->whereHas($relation, $closure)->with($with)->get($columns);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string|array|\Closure $where
     * @return \Exception|int
     */
    public static function deleteWhere($where)
    {
        try {
            return self::query()->where($where)->delete();
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param string|array|\Closure $where
     * @param array $data
     * @return \Exception|int
     */
    public static function updateWhere($where, array $data)
    {
        try {
            return self::query()->where($where)->update($data);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
