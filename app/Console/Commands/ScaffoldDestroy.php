<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScaffoldDestroy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:destroy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will be destroyed before the entire scaffolds created';

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
        //
    }
}
