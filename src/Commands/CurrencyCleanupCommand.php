<?php

namespace Koddea\Currency\Commands;

use Illuminate\Console\Command;
use Cache;

class CurrencyCleanupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'currency:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup currency cache';

    /**
     * Application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {
        Cache::forget('koddea.currency');

        $this->info('Currency cache cleaned.');
    }
}
