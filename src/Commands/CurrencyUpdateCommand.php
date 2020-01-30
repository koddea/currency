<?php

namespace Koddea\Currency\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use DB;
use Cache;
use DateTime;

class CurrencyUpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange rates from Yahoo';

    /**
     * Application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Currencies table name.
     *
     * @var string
     */
    protected $table_name;

    /**
     * Create a new command instance.
     *
     * @param Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->table_name = $app['config']['currency.table_name'];

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Get Settings
        $defaultCurrency = $this->app['config']['currency.default'];

        // Get rates
        $this->updateFromTCMB($defaultCurrency);
    }

    private function updateFromTCMB($defaultCurrency)
    {
        $this->info('Updating currency exchange rates from TCMB...');

        $connect_web = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');

        if ($connect_web) {
            foreach ($connect_web->Currency as $currency){

                $code = ($currency->attributes()->Kod[0]);
                $value = $currency->ForexSelling;

                $curr = $this->app['db']->table($this->table_name)
                    ->where('code', $code)->first();

                try{

                    if($curr){
                        $this->app['db']->table($this->table_name)
                            ->where('code', $code)
                            ->update([
                                'value' => 1/((double)$value),
                                'updated_at' => new DateTime('now'),
                            ]);
                    }


                }catch (\Exception $e){
                    LOG.error("CurrencyUpdateCommand", $e);
                }


            }

            Cache::forget('koddea.currency');
        }

        $this->info('Update!');
    }

}
