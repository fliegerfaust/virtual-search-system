<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \SletatWebClient;

/**
 * Команда для импорта данных от sletat в таблицы БД countries, resorts etc...
 */

class SletatImportData extends Command {

	protected $name = 'sletat:import-data';

	protected $description = 'Importing data from sletat web-service.';

	public function __construct()
	{
		parent::__construct();
	}	

	public function fire()
	{

		DB::connection()->disableQueryLog();

		$swc = App::make('sletat-web-client');
		
		// Сначала забираем города вылета и записываем их в БД.
		// Если города в нашей базе нет, то записываем его, инчае -- 
		// обновляем данные. Аналогичная схема с остальными объектами.
		echo "Starting <Depart Cities> import...\n\n";
		$depCities = $swc->getDepartCities()->GetDepartCitiesResult->City;
		foreach ($depCities as $sletatCity) {
			$sletatCityId = $sletatCity->Id;
			$city = (DepartCity::where(array ('sletat_id' => $sletatCityId))->first());
			if (!$city) {
				$city = new DepartCity;
				$city->sletat_id = $sletatCityId;
			}
			$city->name = $sletatCity->Name;
			$city->save();
		}
		echo "Import <Depart Cities> is done!\n\n";

		// Далее забираем информацию о странах, туры в которые доступны 
		// из городов вылета
		echo "Starting <Countries> import...\n\n";
		$depCities = DepartCity::all(); 
		foreach ($depCities as $depCity) {
			$countries = $swc->getCountries($depCity->sletat_id)->GetCountriesResult->Country;
			foreach ($countries as $sletatCountry) {
				$sletatCountryId = $sletatCountry->Id;
				$country = Country::with('depart_cities')->where(array ('sletat_id' => $sletatCountryId))->first();
				if (!$country) {
					$country = new Country;
					$country->sletat_id = $sletatCountryId;
				}
				$country->name = $sletatCountry->Name;
				$country->save();
				try {
					if (!$country->depart_cities()->get()->contains($depCity->id)) {
						$country->depart_cities()->save($depCity);
					} 
				} catch (Exception $e) { 
					echo $e;
					return;
				}
			}
		}
		echo "Import <Countries> is done!\n\n";

		// Далее забираем инфу о курортах(городах отдыха в странах)
		echo "Starting <Resorts> import...\n\n";
		$countries = Country::all();
		foreach ($countries as $country) {
			$sletatQueryResult = $swc->getGetResorts($country->sletat_id)->GetCitiesResult;
			// Ставим костыли для отбрасывания кривой информации от sletat.ru
			if (!isset($sletatQueryResult->City)) {
				var_dump($sletatQueryResult);
				echo "Ignoring Country [{$country->id}] {$country->name}\n\n";
				continue;
			} else {
				echo "Importing Country [{$country->id}] {$country->name}\n\n";
			}
			$resorts = $sletatQueryResult->City;
			foreach ($resorts as $sletatResort) {
				if (!is_object($sletatResort) || !isset($sletatResort->Id)) {
					continue;
				}
				$sletatResortId = $sletatResort->Id;
				$resort = Resort::with('country')->where(array ('sletat_id' => $sletatResortId))->first();
				if (!$resort) {
					$resort = new Resort;
					$resort->sletat_id = $sletatResortId;
				}
				$resort->name = $sletatResort->Name;
				$resort->country()->associate($country);
				try {
					$resort->save();
				} catch (\Illuminate\Database\QueryException $e) {
					echo $e;
					return;
				}
			}
		}
		echo "Import <Resorts> is done!\n\n";

		// Далее забираем инфу об отелях в городах отдыха
		echo "Starting <Hotels> import...\n\n";
		$countries = Country::with('resorts')->get();
		foreach ($countries as $country) {
			foreach ($country->resorts()->get() as $resort) {
				$sletatQueryResult = $swc->getHotels($country->sletat_id, array ($resort->sletat_id), null, null, -1)->GetHotelsResult;
				// Ставим костыли для отбрасывания кривой информации от sletat.ru
				if (!isset($sletatQueryResult->Hotel)) {
					var_dump($sletatQueryResult);
					echo "Ignoring [{$resort->id}] {$resort->name} - Country [{$country->id}] {$country->name}\n\n";
					continue;
				} else {
					echo "Importing Resort [{$resort->id}] {$resort->name} - Country [{$country->id}] {$country->name}\n\n";
				}
				$hotels = $sletatQueryResult->Hotel;
				foreach ($hotels as $sletatHotel) {
					if (!is_object($sletatHotel) || !isset($sletatHotel->Id)) {
						continue;
					}
					$sletatHotelId = $sletatHotel->Id;
					$hotel = Hotel::with('resort')->where(array ('sletat_id' => $sletatHotelId))->first();
					if (!$hotel) {
						$hotel = new Hotel;
						$hotel->sletat_id = $sletatHotelId;
					}
					$hotel->name = $sletatHotel->Name;
					$hotel->star_name = $sletatHotel->StarName;
					$hotel->rate = (round($sletatHotel->Rate*100));
					$hotel->resort()->associate($resort);
					try {
						$hotel->save(); 
					} catch (\Illuminate\Database\QueryException $e) {
						echo $e;
						return;
					}
				}
			}
		}
		echo "Import <Hotels> is done!\n\n";

	}

	protected function getArguments()
	{
		return array();
	}

	protected function getOptions()
	{
		return array();
	}

}