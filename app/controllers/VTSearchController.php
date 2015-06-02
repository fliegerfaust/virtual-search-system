<?php
class VTSearchController extends BaseController {

/**
* Контроллер поиска туров.
* Поиск туров осуществляется с помощью методов класса /app/lib/SletatWebClient.php
*/
	
	/**
	 * createSearchRequest Получение Id поискового запроса по заданным параметрам
	 * 						Параметры извлекаются из суперглобальной переменной 
	 * 						$_REQUEST. Предварительно проходят фильтрацию.
	 * 						@see  app/filters
	 * @var    int    $depCityId Город отправления
	 * @var    int 	  $targetType Тип цели 	
	 * @var    int    $targetId Страна/курорт/отель
	 * @var    int    $departDate Дата отправления
	 * @var    int    $nightsNum Количесто ночей
	 * @var    int    $adults Количество взрослых
	 * @var    array  $kids Массив возрастов детей в виде [13, 14, 14]
	 * @var    int    $hotelCategoryId Категория отеля
	 * @return int    $sletatSearchRequestId
	 */
	public function createSearchRequest() {

		$filteredParams = $_REQUEST['tourParams'];

		$depCityId = $filteredParams["depCityId"];
		$targetType = $filteredParams["targetType"];
		$targetId = $filteredParams["targetId"];
		$departDate = $filteredParams["departDate"];
		$nightsNum = $filteredParams["nightsNum"];
		$adults = $filteredParams["adults"];
		$kids = $filteredParams["kids"];
		$hotelCategoryId = $filteredParams["hotelCategoryId"];
		$currencyAlias = 'RUB';
		
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


				$departDate = date('d.m.Y', strtotime($departDate));
				$departDateFrom = date('d.m.Y', strtotime($departDate) + (24*3600*$country->getDDateFromValue()));
				$departDateTo = date('d.m.Y', strtotime($departDate) + (24*3600*$country->getDDateToValue()));

				if ((isset($filteredParams["deltaDateFrom"])) && (isset($filteredParams["deltaDateTo"]))) {
					$departDateFrom = $filteredParams["deltaDateFrom"];
					$departDateTo = $filteredParams["deltaDateTo"];
				}

				$nightsMin = $nightsNum + $country->getDNightFromValue();
				$nightsMax = $nightsNum + $country->getDNightToValue();

				if ((isset($filteredParams["deltaNightsFrom"])) && (isset($filteredParams["deltaNightsTo"]))) {
					$nightsMin = $filteredParams["deltaNightsFrom"];
					$nightsMax = $filteredParams["deltaNightsTo"];
				}

				break;
			case 2:
				// Получаем sletat_id курорта, получаем sletat_id страны, отели - пусто
				$resort = Resort::with('country')->where(array ('id' => $targetId))->first();
				$resortId = array($resort->sletat_id);
				$localCountryId = $resort->country_id;
				$countryId = Country::where(array ('id' => $localCountryId))->first()->sletat_id;
				$hotelId = array();

				$departDate = date('d.m.Y', strtotime($departDate));
				$departDateFrom = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateFromValue()));
				$departDateTo = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateToValue()));

				if ((isset($filteredParams["deltaDateFrom"])) && (isset($filteredParams["deltaDateTo"]))) {
					$departDateFrom = $filteredParams["deltaDateFrom"];
					$departDateTo = $filteredParams["deltaDateTo"];
				}

				$nightsMin = $nightsNum + $resort->getDNightFromValue();
				$nightsMax = $nightsNum + $resort->getDNightToValue();

				if ((isset($filteredParams["deltaNightsFrom"])) && (isset($filteredParams["deltaNightsTo"]))) {
					$nightsMin = $filteredParams["deltaNightsFrom"];
					$nightsMax = $filteredParams["deltaNightsTo"];
				}

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

				$departDate = date('d.m.Y', strtotime($departDate));
				$departDateFrom = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateFromValue()));
				$departDateTo = date('d.m.Y', strtotime($departDate) + (24*3600*$resort->getDDateToValue()));

				if ((isset($filteredParams["deltaDateFrom"])) && (isset($filteredParams["deltaDateTo"]))) {
					$departDateFrom = $filteredParams["deltaDateFrom"];
					$departDateTo = $filteredParams["deltaDateTo"];
				}

				$nightsMin = $nightsNum + $resort->getDNightFromValue();
				$nightsMax = $nightsNum + $resort->getDNightToValue();

				if ((isset($filteredParams["deltaNightsFrom"])) && (isset($filteredParams["deltaNightsTo"]))) {
					$nightsMin = $filteredParams["deltaNightsFrom"];
					$nightsMax = $filteredParams["deltaNightsTo"];
				}

				break;
		}
		$kidsNum = count($kids);
		switch ($hotelCategoryId) {
			// 2* - 401, 3* - 402, 4* - 403, 5* - 404, Apts - 405, Villas - 406, HV-1 - 410, HV-2 - 411
			case 401:
				$hotelStars = array(401, 402, 403, 404, 405, 406, 410, 411);
				break;
			case 402:
				$hotelStars = array(402, 403, 404, 405, 406, 410, 411);
				break;
			case 403:
				$hotelStars = array(403, 404, 405, 406, 410, 411);
				break;
			case 404:
				$hotelStars = array(404, 405, 406, 410, 411);
				break;
			case 405:
				$hotelStars = array(405, 406, 410, 411);
				break;
			case 406:
				$hotelStars = array(406, 410, 411);
				break;
			case 410:
				$hotelStars = array(410, 411);
			case 411:
				$hotelStars = array(411);
				break;
			default:
				$hotelStars = array();
				break;
		}

		// Создаём новый запрос на поиск тура и получаем ID (поискового) запроса
		$sletatSearchRequestId = $swc->createRequest($countryId, $depCityId, $resortId, null, $hotelStars,
			$hotelId, $adults, $kidsNum, $kids, $nightsMin, $nightsMax, null, null, $currencyAlias, $departDateFrom, $departDateTo, 
			null, null, null, null, null, null, null)->CreateRequestResult;

		return $sletatSearchRequestId;
	}

	/**
	 * getToursData Получение данных о загруженных турах по Id поискового запроса
	 * @return JSON {"state" => '', "tours" => ''}
	 */
	public function getToursData() {

		$requestId = $_REQUEST['requestId'];

		$swc = App::make('sletat-web-client');

		$result = array("state" => 'finish', "tours" => array());

		if ($swc->getRequestResult($requestId) != 'nonexistent RequestId') {

			$requestResult = $swc->getRequestResult($requestId)->GetRequestResultResult;

			$waiting = 0;
			$ready = 0;
			foreach ($requestResult->LoadState->OperatorLoadState as $requestOperatorLoadState) {
				if ($requestOperatorLoadState->IsProcessed == false) {
					$waiting++;
				} else {
					$ready++;
				}
			}

			foreach ($requestResult->LoadState->OperatorLoadState as $requestOperatorLoadState) {
				if ($requestOperatorLoadState->IsProcessed == false) {
					$result["state"] = 'progress';
					$result["ready"] = $ready;
					$result["waiting"] = $waiting;
					break;
				}
			}

			if ($result["state"] == 'finish') {
				try {
					$toursRaw = $requestResult->Rows->XmlTourRecord;

					// Группируем туры по отелям
					$hotels = array();
					foreach ($toursRaw as $tourOffer) {
						$hid = $tourOffer->HotelId;
						if (!isset($hotels[$hid])) {
							// Tour hotel ID
							$hid = $tourOffer->HotelId;
							$hotels[$hid] = array();
							$hotels[$hid]['Adults'] = $tourOffer->Adults;
							$hotels[$hid]['Kids'] = $tourOffer->Kids;
							$hotels[$hid]['Nights'] = $tourOffer->Nights;
							$hotels[$hid]['ResortId'] = $tourOffer->ResortId;
							$hotels[$hid]['ResortName'] = $tourOffer->ResortName;
							$hotels[$hid]['StarId'] = $tourOffer->StarId;
							$hotels[$hid]['StarName'] = $tourOffer->StarName;
							$hotels[$hid]['TicketsIncluded'] = $tourOffer->TicketsIncluded;
							$hotels[$hid]['CheckInDate'] = $tourOffer->CheckInDate;
							$hotels[$hid]['CountryId'] = $tourOffer->CountryId;
							$hotels[$hid]['CountryName'] = $tourOffer->CountryName;
							$hotels[$hid]['Description'] = $tourOffer->Description;
							$hotels[$hid]['HotelId'] = $tourOffer->HotelId;
							$hotels[$hid]['HotelIsInStop'] = $tourOffer->HotelIsInStop;
							$hotels[$hid]['HotelDescriptionUrl'] = $tourOffer->HotelDescriptionUrl;
							$hotels[$hid]['HotelName'] = $tourOffer->HotelName;
							$hotels[$hid]['HotelRating'] = $tourOffer->HotelRating;
							$hotels[$hid]['HotelTitleImageUrl'] = $tourOffer->HotelTitleImageUrl;
							$hotels[$hid]['SourceId'] = $tourOffer->SourceId;	

							$hotels[$hid]['offers'] = array();
						}

						// Tour offer ID
						$oid = $tourOffer->OfferId; 
						$hotels[$hid]['offers'][$oid]['RoomId'] = $tourOffer->RoomId;
						$hotels[$hid]['offers'][$oid]['RoomName'] = $tourOffer->RoomName;
						$hotels[$hid]['offers'][$oid]['MealId'] = $tourOffer->MealId;
						$hotels[$hid]['offers'][$oid]['MealName'] = $tourOffer->MealName;
						$hotels[$hid]['offers'][$oid]['MealDescription'] = $tourOffer->MealDescription;
						$hotels[$hid]['offers'][$oid]['HtPlaceId'] = $tourOffer->HtPlaceId;
						$hotels[$hid]['offers'][$oid]['HtPlaceName'] = $tourOffer->HtPlaceName;
						$hotels[$hid]['offers'][$oid]['Price'] = $tourOffer->Price;

					}
					$result["tours"] = $hotels;
				} catch (Exception $e) {
					$result["state"] = 'error: XmlTourRecord does not exist';
				}
			}
		} else {
			$result["state"] = 'error';
		}

	return json_encode($result);
	}

	/**
	 * getMinPrice получить минимальную цену тура по заданному направлению
	 * @return int $minPrice: -1, если нет цены, -2, если нет туров по направлению
	 * @see  VTSearchController
	 */
	public function getMinPrice() {

		if (App::environment('local', 'work-pc')) {
			$artisanPath = "./artisan";
		} else {
			$artisanPath = "../artisan";
		}

		$filteredParams = $_REQUEST["tourParams"];

		$depCityId = $filteredParams["depCityId"];
		$targetType = $filteredParams["targetType"];
		$targetId = $filteredParams["targetId"];
		// Задаем "старость" записи в кэше (мин)
		$storageTimeMin = 60;

		$result = array("state" => 'finish', "minPrice" => '');
		if (Cache::has($depCityId.$targetType.$targetId)) {
			$cacheValue = Cache::get($depCityId.$targetType.$targetId);
			$value = explode('|', $cacheValue);
			$minPrice = $value[0];
			$cacheTime = $value[1];

			if ($cacheTime < Carbon::now()->subMinutes($storageTimeMin)) {
				// Если величина старше, чем N минут,
				// запуск команды кеширования
				$minPrice = -1;
				echo exec('php '.$artisanPath.' sletat:cache-min-price '.$depCityId.' '.$targetType.' '.$targetId.' &');
			}
		} else {
			// Если в кеше нет данных по заданному направлению
			// запуск команды кеширования,
			$minPrice = -1;
			echo exec('php '.$artisanPath.' sletat:cache-min-price '.$depCityId.' '.$targetType.' '.$targetId.' &');
		}
		return $minPrice;
	}

	public function saveTourOrder() {

		$swc = App::make('sletat-web-client');

		$filteredParams = $_REQUEST["tourParams"];

		$requestId = $filteredParams["requestId"];
		$offerId = $filteredParams["offerId"];
		$sourceId = $filteredParams["sourceId"];
		$username = $filteredParams["username"];
		$email = $filteredParams["email"];
		$phone = $filteredParams["phone"];

		$info = isset($filteredParams["info"]) ? $filteredParams["info"] : '';

		$result = array("state" => '');

		try {
			$swc->saveTourOrder($requestId, $offerId, $sourceId, $username, $email, $phone, $info);
			$result["state"] = "Tour order has been successfully sent!";
		} catch (Exception $e) {
			$result["state"] = $e;
		}

		return $result;
	}

	public function getHotelInfo() {

		$swc = App::make('sletat-web-client');

		$filteredParams = $_REQUEST["hotelParams"];

		$hotelId = $filteredParams["hotelId"];
		$cssStyleSheet = $filteredParams["cssStyleSheet"];
		$resultHotelInfo = array();

		try {
			$sletatHotelId = Hotel::where(array ('id' => $hotelId))->first()->sletat_id;
		} catch (Exception $e) {
			$badResult = array("state" => -1, "message" => "Hotel not found!");
			return $badResult;
		}
		$result = $swc->getHotelInformation($sletatHotelId, $cssStyleSheet)->GetHotelInformationResult;

		// Костыль для обхода поля Description с iframe-ом от sletat.ru
		foreach ($result as $hotelInfo) {
			if ((is_string($hotelInfo)) && (strpos($hotelInfo, "iframe") <> 0)) {
				continue;
			}
			array_push($resultHotelInfo, $hotelInfo);
		}

		return json_encode($resultHotelInfo);
	}
}