<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class HDImportData extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hd:import-data';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import geographic and hotels database from HotelDiscount';

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
	
	public function correctName($uncorrectName) 
	{
		$namesCorrector = file(storage_path() . '/names_corrector.txt');
		foreach ($namesCorrector as $name) {
				$explodedName = explode("|", $name);
				if ($explodedName[0] == $uncorrectName) {
					$correctName = trim($explodedName[1]);
					return $correctName;
				}
		}
		return $uncorrectName;
	}

	public function fire()
	{
		//
		$geo = simplexml_load_file(storage_path() . '/hd_data/Geo.xml', null, LIBXML_NOCDATA);
		$countriesCount = 0;
		$citiesCount = 0;

		$regions = array();

		foreach ($geo->Country as $country) {

			$countriesCount++;

			$region = array(
				'id' => (int) $country->attributes()->Id,
				'name' => (string) $country->Name,
				'name_eng' => (string) $country->Name_eng,
				'visa_support' => $country->VisaSupport == "Yes"
				);	

			$correctedName = $this->correctName($region['name']);

			$localCountry = Country::where(array ('name' => $correctedName))->first();
			if (false&&!$localCountry) {
				echo "Country {$region['name']} not found\n";
			}

			// временно вырубаем обход городов/курортов
			if ($country->Cities->City->count() > 0) {
				$cities = array();
				foreach ($country->Cities->City as $city) {
					$cities[(int) $city->attributes()->Id] = array(
						'id' => (int) $city->attributes()->Id,
						'name' => (string) $city->Name,
						'name_eng' => (string) $city->Name_eng,
						'favorite' => (int) $city->IsFavorite,	
						'latitude' => (string) $city->Latitude,
						'longitude' => (string) $city->Longitude, 
						);

					$localResort = Resort::where(array ('name' => $city->Name))->first();
					if (!$localResort) {
						echo "Resort {$city->Name} not found\n";
					}
					$citiesCount++;
				}
				$region['cities'] = $cities;
			}
			$regions[] = $region;

			// var_dump($region);
			// break;
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
