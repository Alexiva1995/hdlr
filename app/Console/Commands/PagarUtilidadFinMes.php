<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CierreComisionController;

class PagarUtilidadFinMes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Pagar:utilidad';
    public $CierreComisionController;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->CierreComisionController = new CierreComisionController();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->CierreComisionController->pagarUtilidadFinDeMes();
        return 0;
    }
}
