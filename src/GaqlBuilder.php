<?php

declare(strict_types=1);

namespace MkRyan1988\GaqlBuilder;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GaqlBuilder
{
    protected GaqlCompiler $compiler;

    /**
     * The fields that should be returned
     */
    public array $fields;

    /**
     * The resource the query is targeting
     */
    public string $from;

    /**
     * The where constraints for the query.
     */
    public array $wheres = [];

    /**
     * The orderings for the query.
     */
    public array $orders;

    /**
     * The maximum number of records to return.
     */
    public ?int $limit = null;

    /**
     * All of the available clause operators.
     */
    public array $operators = [
        '=', '<', '>', '<=', '>=', '!=',
        'like', 'not like', 'regexp_match', 'not regexp_match',
        'is null', 'is not null', 'between', 'during', 'in',
    ];

    /**
     * Create a new gaql builder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->compiler = new GaqlCompiler();
    }

    /**
     * Build the actual query string
     */
    public function build(): string
    {
        return trim($this->compiler->compileComponents($this));
    }

    /**
     * Set the resource the query is targeting
     */
    public function from(string $resource): GaqlBuilder
    {
        $this->from = $resource;

        return $this;
    }

    /**
     * Alias to set the "limit" value of the query.
     */
    public function resource(string $resource): GaqlBuilder
    {
        $this->from = $resource;

        return $this;
    }

    /**
     * Set the fields to be selected.
     */
    public function select($fields): GaqlBuilder
    {
        $this->fields = is_array($fields) ? $fields : func_get_args();

        return $this;
    }

    /**
     * Add a where filter to the query.
     *
     * @param string $field
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function where(string $field, $operator = null, $value = null, $boolean = 'and'): GaqlBuilder
    {
        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the '=' operators and
        // we will set the operators to '=' and set the values appropriately.
        if ($this->invalidOperator($operator)) {
            [$value, $operator] = [$operator, '='];
        }

        // If the value is "null", we will just assume the developer wants to add a
        // where null clause to the query. So, we will allow a short-cut here to
        // that method for convenience so the developer doesn't have to check.
        if (is_null($value)) {
            return $this->whereNull($field, $boolean, $operator !== '=');
        }

        $type = 'Basic';

        // Now that we are sure we have a valid where filter we will
        // add it to our $wheres array
        $this->wheres[] = compact(
            'type', 'field', 'operator', 'value', 'boolean'
        );

        return $this;
    }

    /**
     * Add a "where null" clause to the query.
     *
     * @param string $field
     * @param  string  $boolean
     * @param  bool  $not
     * @return $this
     */
    public function whereNull(string $field, $boolean = 'and', $not = false): GaqlBuilder
    {
        [$type, $operator, $value] = [
            $not ? 'NotNull' : 'Null',
            $not ? 'IS NOT NULL' : 'IS NULL',
            ''
        ];

        foreach (Arr::wrap($field) as $field) {
            $this->wheres[] = compact('type', 'field', 'operator', 'value', 'boolean');
        }

        return $this;
    }

    /**
     * Add a "where null" clause to the query.
     *
     * @param string $field
     * @param  string  $boolean
     * @return $this
     */
    public function whereNotNull(string $field, $boolean = 'and'): GaqlBuilder
    {
        return $this->whereNull($field, $boolean, true);
    }

    /**
     * Add a "where null" clause to the query.
     *
     * @param string $field
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function whereDuring(string $field, $value, $boolean = 'and'): GaqlBuilder
    {
        $type = 'during';
        $operator = strtoupper($type);

        foreach (Arr::wrap($field) as $field) {
            $this->wheres[] = compact('type', 'field', 'operator', 'value', 'boolean');
        }

        return $this;
    }

    public function whereBetween($field, array $values, $boolean = 'and', $not = false): GaqlBuilder
    {
        $type = 'between';
        $operator = strtoupper($type);

        $this->wheres[] = compact('type', 'field', 'operator', 'values', 'boolean', 'not');

        return $this;
    }

    public function whereIn(string $field, array $values, $boolean = 'and', $not = false): GaqlBuilder
    {

        $type = 'In';
        $operator = strtoupper($type);

        $this->wheres[] = compact('type', 'field', 'operator', 'values', 'boolean', 'not');

        return $this;
    }

    public function whereNotIn(string $field, $values, $boolean = 'and'): GaqlBuilder
    {
        return $this->whereIn($field, $values, $boolean, true);
    }

    public function orderBy(string $field, $direction = 'asc'): GaqlBuilder
    {
        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $this->orders[] = compact('field', 'direction');

        return $this;
    }

    /**
     * Set the limit value of the query
     */
    public function limit(int $value): GaqlBuilder
    {
        $this->limit = $value ?? null;

        return $this;
    }

    /**
     * Alias to set the "limit" value of the query.
     */
    public function take(int $value): GaqlBuilder
    {
        return $this->limit($value);
    }

    /**
     * Determine if the given operator is supported.
     */
    protected function invalidOperator(string $operator): bool
    {
        return ! in_array(strtolower($operator), $this->operators, true);
    }

    /**
     * Prepare the value and operator for a where clause.
     */
    protected function prepareValueAndOperator($value, $operator, $useDefault = false): array
    {
        if ($useDefault) {
            return [$operator, '='];
        }

        return [$value, $operator];
    }

    /**
     * Determine if the given operator and value combination is legal.
     *
     * Prevents using Null values with invalid operators.
     *
     * @param string $operator
     * @param  mixed  $value
     * @return bool
     */
    protected function invalidOperatorAndValue(string $operator, $value): bool
    {
        return is_null($value) && in_array($operator, $this->operators) &&
            ! in_array($operator, ['=', '<>', '!=']);
    }
}
