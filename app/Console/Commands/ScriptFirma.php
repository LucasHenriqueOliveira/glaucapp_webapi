<?php

namespace App\Console\Commands;

use App\Data\Utils;
use Illuminate\Console\Command;

class ScriptFirma extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script:firma';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rodando script para incluir novas firmas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		Utils::script();
    }
}
