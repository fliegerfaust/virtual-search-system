<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DevCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'vss:dev-testing';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for testing dev constructions.';

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
	public function fire()
	{	
		// Here testing code
		// $model = new VTConstant;
		// $result = $model->getValueByName('verotour');
		// $depCityId = 2;
		// $targetType = 1;
		// $targetId = 34;
		// $result = Cache::get($depCityId.$targetType.$targetId);
		// $time = explode('|', $result);
		// var_dump($result);
		// // $timeNow = Carbon::now();
		// if ($time[1] < Carbon::now()->subMinutes(60)) {
		// 	var_dump('Older than 60 minutes!');
		// } else { 
		// 	var_dump('Younger than 60 minutes!');
		// }
		// var_dump();
		// $hotel = Hotel::where(array("id" => 1))->first();
		// var_dump($hotel->getDDateFromValue());
		

		// Получить доступ к полю "text" после перевода
		$resortName = "Torre Canne Di Fasano";
		$url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20140916T064418Z.e41adb9bd7972573.fd7f688794be54fa7b67854b33b9d26cb8c8e02b&text={$resortName}&lang=en-ru";
		$executedLine = file_get_contents($url);
		$translatedName = json_decode($executedLine)->text[0];
		echo "{$translatedName}\n";

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
