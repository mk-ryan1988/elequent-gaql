<?php

declare(strict_types=1);

namespace MkRyan1988\EloquentGaql;

class GaqlCompiler
{
    /**
     * The components that make up a gaql query.
     */
    protected array $gaqlComponents = [
        'selects',
        'from',
        'wheres',
        'orders',
        'limit',
    ];

    protected GaqlBuilder $builder;

    public function compileComponents(GaqlBuilder $builder): string
    {
        $this->builder = $builder;

        $compiledComponents = [];
        foreach ($this->gaqlComponents as $component) {
            $method = 'compile' . ucfirst($component);
            $compiledComponents[] = $this->$method();
        }

        return $this->concatenate($compiledComponents);
    }

    private function compileSelects(): string
    {
        return 'SELECT ' . implode(', ', $this->builder->fields);
    }

    private function compileFrom(): string
    {
        return 'FROM ' . $this->builder->from;
    }

    private function compileLimit(): string
    {
        if (! $this->builder->limit) {
            return '';
        }

        return 'LIMIT ' . $this->builder->limit;
    }

    private function compileOrders(): string
    {
        if (empty($this->builder->orders)) {
            return '';
        }

        $strings = [];
        foreach ($this->builder->orders as $order) {
            $strings[] = $order['field'] . ' ' . strtoupper($order['direction']);
        }

        return 'ORDER BY ' . implode(', ', $strings);
    }

    private function compileWheres(): string
    {
        if (empty($this->builder->wheres)) {
            return '';
        }

        $strings = [];
        foreach ($this->builder->wheres as $where) {
            $method = 'compileWhere' . ucfirst($where['type']);

            $strings[] = trim($this->$method($where));
        }

        return 'WHERE ' . implode(' AND ', $strings);
    }

    private function compileWhereBasic(array $where, bool $wrap = true): string
    {
        $value = $wrap ? $this->quote((string)$where['value']) : $where['value'];

        return $where['field'] . ' ' . $where['operator'] . ' ' . $value;
    }

    private function compileWhereNull(array $where): string
    {
        return $this->compileWhereBasic($where, false);
    }

    private function compileWhereNotNull(array $where): string
    {
        return $this->compileWhereBasic($where, false);
    }

    private function compileWhereDuring(array $where): string
    {
        $where['value'] = strtoupper($where['value']);

        return $this->compileWhereBasic($where, false);
    }

    private function compileWhereBetween(array $where): string
    {
        $operator = $this->appendNotOperator($where);

        $where['value'] = reset($where['values']);

        $max = end($where['values']);

        return $this->compileWhereBasic($where) . ' AND ' . $this->quote($max);
    }

    private function compileWhereIn(array $where): string
    {
        $operator = $this->appendNotOperator($where);

        $quote = function ($value) {
            return $this->quote($value);
        };

        $values = implode(', ', array_map($quote, $where['values']));

        return $where['field'] . ' ' . $operator . ' (' . $values . ')';
    }

    private function quote(string $value): string
    {
        if (empty($value)) {
            return '';
        }

        return '\'' . $value . '\'';
    }

    private function appendNotOperator(array $where): string
    {
        if ($where['not']) {
            return 'NOT ' . $where['operator'];
        }

        return $where['operator'];
    }

    private function concatenate(array $values): string
    {
        $strings = [];

        foreach ($values as $value) {
            if (empty($value)) {
                continue;
            }
            $strings[] = trim($value);
        }

        return implode(' ', $strings);
    }
}
