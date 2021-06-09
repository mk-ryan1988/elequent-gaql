<?php

namespace MkRyan1988\GaqlBuilder\Tests;

use MkRyan1988\GaqlBuilder\GaqlBuilder;

class GaqlBuilderTest extends TestCase
{
    public GaqlBuilder $gaqlBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->gaqlBuilder = new GaqlBuilder();
    }

    /** @test */
    public function baselineSelectQuery() {
        $query = 'SELECT billing_setup.id, '
            . 'billing_setup.status, '
            . 'billing_setup.payments_account_info.payments_account_id, '
            . 'billing_setup.payments_account_info.payments_account_name, '
            . 'billing_setup.payments_account_info.payments_profile_id, '
            . 'billing_setup.payments_account_info.payments_profile_name, '
            . 'billing_setup.payments_account_info.secondary_payments_profile_id '
            . 'FROM billing_setup';

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup';

        $this->assertEquals($string, $query);
    }

    /** @test */
    public function simpleSelectQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup');

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function simpleSelectResourceQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->resource('billing_setup');

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function limitSelectQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->limit(1);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function orderSelectQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->orderBy('billing_setup.end_time_type')
            ->orderBy('billing_setup.status', 'desc')
            ->limit(10);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup ORDER BY billing_setup.end_time_type ASC, billing_setup.status DESC LIMIT 10';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function invalidOrderSelectQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->orderBy('billing_setup.end_time_type')
            ->orderBy('billing_setup.status', 'sideways')
            ->limit(10);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup ORDER BY billing_setup.end_time_type ASC, billing_setup.status ASC LIMIT 10';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function baselineSimpleWhereQuery() {
        $query = 'SELECT metrics.clicks, '
            . 'metrics.cost_micros, '
            . 'metrics.impressions, '
            . 'metrics.conversions, '
            . 'metrics.all_conversions, '
            . 'metrics.conversions_value '
            . 'FROM customer '
            . 'WHERE segments.date >= \'2021-01-01\' '
            . 'AND customer.id = \'123456789\' '
            . 'LIMIT 1';

        $string = 'SELECT metrics.clicks, metrics.cost_micros, metrics.impressions, metrics.conversions, metrics.all_conversions, metrics.conversions_value FROM customer WHERE segments.date >= \'2021-01-01\' AND customer.id = \'123456789\' LIMIT 1';

        $this->assertEquals($string, $query);
    }

    /** @test */
    public function simpleWhereQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'metrics.clicks',
                'metrics.cost_micros',
                'metrics.impressions',
                'metrics.conversions',
                'metrics.all_conversions',
                'metrics.conversions_value',
            )
            ->from('customer')
            ->where('segments.date', '>=', '2021-01-01')
            ->where('customer.id', '=', 123456789)
            ->limit(1);

        $string = 'SELECT metrics.clicks, metrics.cost_micros, metrics.impressions, metrics.conversions, metrics.all_conversions, metrics.conversions_value FROM customer WHERE segments.date >= \'2021-01-01\' AND customer.id = \'123456789\' LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function simpleWhereWithNoValue() {
        $query = $this->gaqlBuilder
            ->select(
                'metrics.clicks',
                'metrics.cost_micros',
                'metrics.impressions',
                'metrics.conversions',
                'metrics.all_conversions',
                'metrics.conversions_value',
            )
            ->from('customer')
            ->where('segments.date', '>=', '2021-01-01')
            ->where('customer.id', '=', null)
            ->limit(1);

        $string = 'SELECT metrics.clicks, metrics.cost_micros, metrics.impressions, metrics.conversions, metrics.all_conversions, metrics.conversions_value FROM customer WHERE segments.date >= \'2021-01-01\' AND customer.id IS NULL LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function simpleWhereWithTake() {
        $query = $this->gaqlBuilder
            ->select(
                'metrics.clicks',
                'metrics.cost_micros',
                'metrics.impressions',
                'metrics.conversions',
                'metrics.all_conversions',
                'metrics.conversions_value',
            )
            ->from('customer')
            ->where('segments.date', '>=', '2021-01-01')
            ->where('customer.id', '=', null)
            ->take(1);

        $string = 'SELECT metrics.clicks, metrics.cost_micros, metrics.impressions, metrics.conversions, metrics.all_conversions, metrics.conversions_value FROM customer WHERE segments.date >= \'2021-01-01\' AND customer.id IS NULL LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function whereNullQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'metrics.clicks',
                'metrics.cost_micros',
                'metrics.impressions',
                'metrics.conversions',
                'metrics.all_conversions',
                'metrics.conversions_value',
            )
            ->from('customer')
            ->where('segments.date', '>=', '2021-01-01')
            ->whereNull('customer.id')
            ->limit(1);

        $string = 'SELECT metrics.clicks, metrics.cost_micros, metrics.impressions, metrics.conversions, metrics.all_conversions, metrics.conversions_value FROM customer WHERE segments.date >= \'2021-01-01\' AND customer.id IS NULL LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function whereNotNullQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'metrics.clicks',
                'metrics.cost_micros',
                'metrics.impressions',
                'metrics.conversions',
                'metrics.all_conversions',
                'metrics.conversions_value',
            )
            ->from('customer')
            ->where('segments.date', '>=', '2021-01-01')
            ->whereNotNull('customer.id')
            ->limit(1);

        $string = 'SELECT metrics.clicks, metrics.cost_micros, metrics.impressions, metrics.conversions, metrics.all_conversions, metrics.conversions_value FROM customer WHERE segments.date >= \'2021-01-01\' AND customer.id IS NOT NULL LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function whereDuringQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->whereDuring('billing_setup.end_date_time', 'TODAY')
            ->limit(1);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup WHERE billing_setup.end_date_time DURING TODAY LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function whereLowerCaseDuringQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->whereDuring('billing_setup.end_date_time', 'today')
            ->limit(1);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup WHERE billing_setup.end_date_time DURING TODAY LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function whereBetweenQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->whereBetween('billing_setup.end_date_time', ['2021-01-01', '2021-01-31'])
            ->limit(1);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup WHERE billing_setup.end_date_time BETWEEN \'2021-01-01\' AND \'2021-01-31\' LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function whereInQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->whereIn('customer.currency_code', ['GB', 'USA', 'AUS'])
            ->limit(1);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup WHERE customer.currency_code IN (\'GB\', \'USA\', \'AUS\') LIMIT 1';

        $this->assertEquals($string, $query->build());
    }

    /** @test */
    public function whereNotQuery() {
        $query = $this->gaqlBuilder
            ->select(
                'billing_setup.id',
                'billing_setup.status',
                'billing_setup.payments_account_info.payments_account_id',
                'billing_setup.payments_account_info.payments_account_name',
                'billing_setup.payments_account_info.payments_profile_id',
                'billing_setup.payments_account_info.payments_profile_name',
                'billing_setup.payments_account_info.secondary_payments_profile_id',
            )
            ->from('billing_setup')
            ->whereNotIn('customer.currency_code', ['GB', 'USA', 'AUS'])
            ->limit(1);

        $string = 'SELECT billing_setup.id, billing_setup.status, billing_setup.payments_account_info.payments_account_id, billing_setup.payments_account_info.payments_account_name, billing_setup.payments_account_info.payments_profile_id, billing_setup.payments_account_info.payments_profile_name, billing_setup.payments_account_info.secondary_payments_profile_id FROM billing_setup WHERE customer.currency_code NOT IN (\'GB\', \'USA\', \'AUS\') LIMIT 1';

        $this->assertEquals($string, $query->build());
    }
}
