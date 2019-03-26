<?php

namespace App\Console\Commands;

use GlobalBlueprint;
use App\IndexRelation;
use Illuminate\Console\Command;

class MigrateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:migrateall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all directories';

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
		$indexRelations = IndexRelation::all();

		foreach ($indexRelations as $indexRelation)
		{
			GlobalBlueprint::migrate($indexRelation->mainDirectory, $indexRelation->subDirectory);
		}
		GlobalBlueprint::migrate('Line', 'Bot');
		GlobalBlueprint::migrate('Landingpage', 'Setting');
    }
}
