<?php

namespace App\Console\Commands;

use App\Http\Controllers\Properties\PropertyController;
use Illuminate\Console\Command;

class RedisReset extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:redisreset';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Redis Reset';

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
		$controller = new PropertyController();

		return $controller->redisReset();
	}
}
