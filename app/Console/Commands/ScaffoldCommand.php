<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use App\Console\Commands\Scaffold\Scaffold;

class ScaffoldCommand extends Command
{
    protected $signature = 'scaffold';

    protected $description = "Makes layout, js/css, table, controller, model, views, seeds, and repository";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Etapas
        // - DataTables
        // - Controllers
        // - Requests
        // - Models
        // - Repositories
        // - routes/web.php
        // - informar para adicionar o módulo no menu
        $scaffold = new Scaffold($this);

        $scaffold->setupLayoutFiles();

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
