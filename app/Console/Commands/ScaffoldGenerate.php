<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use App\Console\Commands\Scaffold\Scaffold;

class ScaffoldGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffolds an entire resource, from model and migration to controller and views';

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
        $scaffold = new Scaffold($this);

        $scaffold->setupLayoutFiles(); // OK

        $scaffold->createLayout();

        $scaffold->createModels();

        $this->info('Please wait a few moments...');

        $this->call('clear-compiled');

        $this->call('optimize');

        $this->info('Done!');
    }

    protected function getArguments()
    {
        return array(
            array('name', InputArgument::OPTIONAL, 'Name of the model/controller.'),
        );
    }
}
