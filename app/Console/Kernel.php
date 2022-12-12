<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Fanky\Crm\Models\Task;
use Fanky\Crm\Mailer;
use DB;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\Test',
        'App\Console\Commands\ParseAllProducts',
        'App\Console\Commands\ParseProfnastil',
        'App\Console\Commands\ParseTruba',
        'App\Console\Commands\ParseList',
		Commands\ImportOld::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{

		$schedule->command('queue:work --daemon')->everyMinute()->withoutOverlapping()
			->sendOutputTo(storage_path() . '/logs/queue-jobs.log');
	}
	//в крон прописать - php artisan schedule:run
}
