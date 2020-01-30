<?php

namespace Koddea\Currency;

use Cache;
use Illuminate\Support\Facades\Input;
use Session;

class Currency
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Default currency.
     *
     * @var string
     */
    protected $code;

    /**
     * All currencies.
     *
     * @var array
     */
    protected $currencies = [];

    /**
     * Create a new instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;

        // Initialize Currencies
        $this->setCacheCurrencies();

        // Check for a user defined currency
        if (Input::get('currency') && array_key_exists(Input::get('currency'), $this->currencies)) {
            $this->setCurrency(Input::get('currency'));
        } elseif (Session::get('currency') && array_key_exists(Session::get('currency'), $this->currencies)) {
            $this->setCurrency(Session::get('currency'));
        } else {
            $this->setCurrency($this->app['config']['currency.default']);
        }
    }

    public function rounded($number, $decimalPlace = 0, $currency = null)
    {
        return $this->style($number, $currency, $decimalPlace);
    }

    public function hasCurrency($currency)
    {
        return isset($this->currencies[$currency]);
    }

    public function setCurrency($currency)
    {
        $this->code = $currency;

        if (Session::get('currency') != $currency) {
            Session::put('currency', $currency);
        }

    }

    /**
     * Return the current currency code.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->code;
    }

    /**
     * Return the current currency if the
     * one supplied is not valid.
     *
     * @return array
     */
    public function getCurrency($currency = '')
    {
        if ($currency && $this->hasCurrency($currency)) {
            return $this->currencies[$currency];
        } else {
            return $this->currencies[$this->code];
        }
    }

    public function convert($number, $fromCurrencyCode, $toCurrencyCode)
    {
        $fromCurrency = $this->getCurrency($fromCurrencyCode);
        $toCurrency = $this->getCurrency($toCurrencyCode);

        return round( $number / $fromCurrency['value'] * $toCurrency['value'], 2);
    }

    /**
     * Initialize Currencies.
     */
    public function setCacheCurrencies()
    {
        $db = $this->app['db'];

        $this->currencies = Cache::rememberForever('koddea.currency', function () use ($db) {
            $cache = [];
            $tableName = $this->app['config']['currency.table_name'];

            foreach ($db->table($tableName)->get() as $currency) {
                $cache[$currency->code] = [
                    'id' => $currency->id,
                    'code' => $currency->code,
                    'name' => $currency->name,
                    'symbol' => $currency->symbol,
                    'numeric_code' => $currency->numeric_code,
                    'value' => $currency->value,
                ];
            }

            return $cache;
        });
    }
}
