<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SletatCacheMinPrice extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sletat:cache-min-price';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Caches the price of the tour in a given direction.';

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
		$depCityId = $this->argument('depCityId');
		$targetType = $this->argument('targetType');
		$targetId = $this->argument('targetId');
		$debugMode = $this->argument('debugMode');

		// Сохраним параметры для создания ключа для кеша
		// вида '$depCity.$targetType.$targetId'
		// Для того, чтобы получать значения из кеша можно было по
		// приходящим Id-шникам, а не получать sletat_id
		$dCIKeyCache = $depCityId;
		$tTKeyCache = $targetType;
		$tIKeyCache = $targetId;

		$todayDate = date('d.m.Y');
		// Константа, которая в дальнейшем будет выдёргиваться
		// из специальной таблицы констант.
		$addConstDays = 30;

		$swc = App::make('sletat-web-client');

		// Получаем sletat_id нашего depCity
		$depCity = DepartCity::where(array ('id' => $depCityId))->first();
		$depCityId = $depCity->sletat_id;

		// 1 - страна, 2 - курорт, 3 - отель
		switch ($targetType) {
			case 1:
				// Получаем sletat_id страны; города и отели - пусто
				$country = Country::where(array ('id' => $targetId))->first();
				$countryId = $country->sletat_id;
				$resortId = array();
				$hotelId = array();

				$departDate = date('d.m.Y', strtotime($todayDate) + 24*3600*$addConstDays);
				$departDateFrom = date('d.m.Y', strtotime($departDate) + (24*3600*$country->getDDateFromValue()));
				$departDateTo = date('d.m.Y', strtotime($departDate) + (24*3600*$country->getDDateToValue()));
				break;
			case 2:
				// Получаем sletat_id курорта, получаем sletat_id страны, отели - пусто
				$resort = Resort::with('country')->where(array ('id' => $targetId))->first();
				$resortId = array($resort->sletat_id);
				$localCountryId = $resort->country_id;
				$countryId = Country::where(array ('id' => $localCountryId))->first()->sletat_id;
				$hotelId = array();

				$departDate = date('d.m.Y', strtotime($todayDate) + 24*3600*$addConstDays);
				$departDateFrom = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateFromValue()));
				$departDateTo = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateToValue()));
				break;
			case 3:
				// Получаем sletat_id отеля, получаем sletat_id курорта, получаем sletat_id страны
				$hotel = Hotel::with('resort')->where(array ('id' => $targetId))->first();
				$hotelId = array($hotel->sletat_id);
				$localResortId = $hotel->resort_id;
				$resort = Resort::with('country')->where(array ('id' => $localResortId))->first();
				$resortId = array($resort->sletat_id);
				$localCountryId = $resort->country_id;
				$countryId = Country::where(array ('id' => $localCountryId))->first()->sletat_id;

				$departDate = date('d.m.Y', strtotime($todayDate) + 24*3600*$addConstDays);
				$departDateFrom = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateFromValue()));
				$departDateTo = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateToValue()));
				break;
		}

		// Параметры можно перезадвать по мере необходимости, например
		// $departDateFrom и $departDateTo перекрываются нужными значениями
		$hotelStars = array(401, 402, 403, 404, 405, 406, 410, 411);
		$hasTickets = true;
		$currencyAlias = 'RUB';
		$nightsMin = 3;
		$nightsMax = 14;
		$departDateFrom = date('d.m.Y', strtotime($todayDate) + (24*3600*1));
		$departDateTo = date('d.m.Y', strtotime($todayDate) + (24*3600*30));

		// Создаём новый запрос на поиск тура и получаем ID (поискового) запроса
		$requestId = $swc->createRequest($countryId, $depCityId, $resortId, null, $hotelStars,
			$hotelId, null, null, null, $nightsMin, $nightsMax, null, null, $currencyAlias, $departDateFrom, $departDateTo, 
			null, $hasTickets, null, null, null, null, 4)->CreateRequestResult;
	
		usleep(200);
		if ($debugMode) {
			echo "RequestId: {$requestId}\n\r";
		}

		$minPrice = 1000000;
		$result = array("state" => 'finish', "minPrice" => $minPrice);
		$startTime = time();
		$timeout = 90;

		// Опрашиваем операторов до тех пор, пока все не отдали результат
		while (true) {
			$requestResult = $swc->getRequestResult($requestId)->GetRequestResultResult;
			$waiting = 0;
			$ready = 0;
			foreach ($requestResult->LoadState->OperatorLoadState as $requestOperatorLoadState) {
				if ($requestOperatorLoadState->IsProcessed == false) {
					$result["state"] = 'progress';
					$waiting++;
				} else {
					$ready++;
				}
			}
			if ($debugMode) {
				echo "Ready: ".$ready." Waiting: ".$waiting." ";
			}
			if ($ready - $waiting == $ready) {
				$result["state"] = 'finish';
				break;
			}
			if ($startTime - time() > $timeout) {
				$result["state"] = 'timeout error';	
				break;
			}
			sleep(1.5);
		}

		// Как только получен ответ от всех операторов, ищем минимальную цену тура
		// по заданному направлению
		if ($result["state"] == 'finish') {
			try {
				$requestResult = $swc->getRequestResult($requestId)->GetRequestResultResult;
				$toursRaw = $requestResult->Rows->XmlTourRecord;
				foreach ($toursRaw as $tourOfferRow) {
					if (($tourOfferRow->Price < $minPrice) && ($tourOfferRow->Price > 3000)) {
						$minPrice = $tourOfferRow->Price;
						$result["minPrice"] = $minPrice;	
					}
				}
				if ($debugMode) {
					echo "Minimum price: " . $minPrice . "\n\r";
				}
			} catch (Exception $e) {
				$result["state"] = $e;
			}
		}
		// Если нет туров по направлению, то возвращаем -2
		$result["minPrice"] = $result["minPrice"] == 1000000 ? -2 : round($result["minPrice"]/2, 0, PHP_ROUND_HALF_DOWN);
		// Данные о минимальной цене хранятся в виде
		// "$dCIKeyCache.$tTKeyCache.$tIKeyCache" => "24500|2014-08-07 10:30:44"
		// 24500 - minPrice, а далее время, когда была цена кеширована
		Cache::forever($dCIKeyCache.$tTKeyCache.$tIKeyCache, $result["minPrice"].'|'.Carbon::now());
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('depCityId', InputArgument::REQUIRED, 'Depart City Id'),
			array('targetType', InputArgument::REQUIRED, 'Target Type Id'),
			array('targetId', InputArgument::REQUIRED, 'Target Id'),
			array('debugMode', InputArgument::OPTIONAL, 'Debug mode is on?', false)
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		// return array(
		// 	array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		// );
		return array();
	}

}
