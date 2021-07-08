<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\InversionController;

class ReinvertirCapital extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reinvertir:capital';
    public $InversionController;

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
        $this->InversionController = new InversionController();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->InversionController->reinvertirCapital();
        return 0;
    }
}
