<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inversion;
use App\Http\Controllers\InversionController;

class CambiarStatusInversion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:inversion';
    public $InversionController;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cambia el estatus de la inversion si ya paso su fecha de vencimiento';

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
        $this->InversionController->checkStatus();
        return 0;
    }
}
