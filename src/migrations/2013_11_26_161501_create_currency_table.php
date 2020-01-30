<?php

use Illuminate\Database\Migrations\Migration;

class CreateCurrencyTable extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = Config::get('currency.table_name');
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        // Create the currency table
        Schema::create($this->tableName, function ($table) {
            $table->increments('id')->unsigned();
            $table->string('code', 3);
            $table->string('name', 255);
            $table->string('symbol', 10);
            $table->inteher('numeric_code',);
            $table->double('value', 15, 8);
            $table->timestamps();
        });

        $currencies = [
            [
                'id' => 1,
                'code' => 'TRY',
                'name' => 'Turkish lira',
                'symbol' => '₺',
                'numeric_code' => 949,
                'value' => 1.00,
                'created_at' => '2019-07-22 23:25:30',
                'updated_at' => '2019-07-22 23:25:30',
            ],
            [
                'id' => 2,
                'code' => 'USD',
                'name' => 'U.S. Dollar',
                'symbol' => '$',
                'numeric_code' => 840,
                'value' => 0.00,
                'created_at' => '2019-11-29 19:51:38',
                'updated_at' => '2019-11-29 19:51:38',
            ],
            [
                'id' => 3,
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'numeric_code' => 978,
                'value' => 0.00,
                'created_at' => '2019-11-29 19:51:38',
                'updated_at' => '2019-11-29 19:51:38',
            ],
            [
                'id' => 4,
                'code' => 'GBP',
                'name' => 'Pound Sterling',
                'symbol' => '£',
                'numeric_code' => 826,
                'value' => 0.00,
                'created_at' => '2019-11-29 19:51:38',
                'updated_at' => '2019-11-29 19:51:38',
            ],
            [
                'id' => 5,
                'code' => 'RUB',
                'name' => 'Russian ruble',
                'symbol' => 'р.',
                'numeric_code' => 643,
                'value' => 0.00,
                'created_at' => '2019-07-22 23:25:30',
                'updated_at' => '2019-07-22 23:25:30',
            ]
        ];

        DB::table($this->tableName)->insert($currencies);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Delete the currency table
        Schema::drop($this->tableName);
    }
}
