<?php

namespace Drocha\Laravel5Scaffold;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ScaffoldModelCommand extends Command
{
    protected $signature = 'scaffold:model';

    protected $description = "Makes table, controller, model, views, seeds, and repository for model";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $scaffold = new Scaffold($this);

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
