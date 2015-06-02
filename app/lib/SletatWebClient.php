<?php

/**
* Веб-клиент к сервису sletat.ru
* Документация - http://static.sletat.ru/Files/Manual/XML_gate_Search.pdf
* 				 http://static.sletat.ru/Files/Manual/XML_extension_hotelbase.pdf
*/

class SletatWebClient
{
	var $soapClient;
	
	/**
	 * __construct Конструктор класса, подключение и авторизация на сервисе SLETAT
	 * @param string $url
	 * @param string $login
	 * @param string $password
	 */
	function __construct($url = "http://module.sletat.ru/XmlGate.svc?singleWSDL",
		$login = "somelogin", $password = "somepassword")
	{
		$authData = (object)array(
			"Login"    => "somelogin",
			"Password" => "somepassword"
			);
		$authHeader = new SoapHeader('urn:SletatRu:DataTypes:AuthData:v1',
			'AuthInfo', $authData);

		$this->soapClient = new SoapClient($url,  array("trace" => true, 
			"exceptions" => true, "encoding" => "utf-8"));
		$this->soapClient->__setSoapHeaders($authHeader);
	}

	/**
	 * Методы класса строим на основе "оборачивания" методов сервиса SLETAT
	 */

	// ===========================Простые методы============================

	// Список городов вылета
	function getDepartCities() {
		return $this->soapClient->GetDepartCities();
	}

	// Список стран, туры в которые есть из указанного города вылета
	function getCountries($depCity) {
		return $this->soapClient->GetCountries((object)array("townFromId" => 
			$depCity));
	}

	// Список курортов для выбранной страны
	function getGetResorts($countryId) {
		return $this->soapClient->GetCities((object)array("countryId" => 
			$countryId));
	}

	// Список доступных категорий отелей в выбранной стране и курорта
	function getHotelStars($countryId, $towns = array()) {
		return $this->soapClient->GetHotelStars((object)array("countryId" => 
			$countryId, "towns" => $towns));
	}

	// Список видов питания
	function getMeals() {
		return $this->soapClient->GetMeals();
	}

	// Список доступных отелей в выбранной стране
	function getHotels($countryId, $towns = array(), $stars = array(), 
		$filter, $count) {
		return $this->soapClient->GetHotels((object)array("countryId" => $countryId,
			"towns" => $towns, "stars" => $stars, "filter" => $filter,
			"count" => $count));
	}

	// Список доступных туроператоров
	function getTourOperators($depCity, $countryId) {
		return $this->soapClient->getTourOperators((object)array("townFromId" => $depCity,
			"countryId" => $countryId));
	}

	// Список доступных дат начала тура для выбранных города вылета, страны, курорта
	function getTourDates($depCity, $countryId, $resorts) {
		return $this->soapClient->GetTourDates((object)array("townFromId" => $depCity,
			"countryId" => $countryId, "resorts" => $resorts));
	}

	// ======================================Методы для загрузки туров===========================================

	// Метод для создания поискового запроса
	// Страница 16-17 документации
	function createRequest($countryId, $depCity, $resorts = array(), $meals = array(),
		$hotel_stars = array(), $hotels = array(), $adults, $kids, $kidsAges = array(), $nightsMin,
		$nightsMax, $priceMin, $priceMax, $currencyType, $departDateFrom, $departDateTo, $hotelIsNotInStop,
		$roundTicket, $ticketsIncluded, $useOperatorsFilter, $f_to_op_id = array(), $includeDescriptions, $cacheMode) {
		return $this->soapClient->CreateRequest((object)array(
			"countryId" => $countryId, 
			"cityFromId" => $depCity, 
			"cities" => $resorts, 
			"meals" => $meals, 
			"stars" => $hotel_stars, 
			"hotels" => $hotels, 
			"adults" => $adults, 
			"kids" => $kids, 
			"kidsAges" => $kidsAges,
			"nightsMin" => $nightsMin, 
			"nightsMax" => $nightsMax, 
			"priceMin" => $priceMin, 
			"priceMax" => $priceMax, 
			"currencyAlias" => $currencyType,
			"departFrom" => $departDateFrom, 
			"departTo" => $departDateTo, 
			"hotelIsNotInStop" => $hotelIsNotInStop, 
			"hasTickets" => $roundTicket,
			"ticketsIncluded" => $ticketsIncluded, 
			"useFilter" => $useOperatorsFilter, 
			"f_to_id" => $f_to_op_id, 
			"includeDescriptions" => $includeDescriptions,
			"cacheMode" => $cacheMode));
	}

	// Метод возвращает статус загрузки туров для каждого туроператора в рамках указанного поискового запроса
	function getRequestState($requestId) {
		return $this->soapClient->GetRequestState((object)array("requestId" => $requestId));
	}

	// Метод возвращает все туры найденные в рамках поискового запроса
	function getRequestResult($requestId) {
		try {
			return $this->soapClient->GetRequestResult((object)array("requestId" => $requestId));			
		} catch (Exception $e) {
			return 'nonexistent RequestId';
		}
	}

	// Метод производит продолжение поиска используя данные указанного поискового запроса
	// Страница 26-27 документации (описание логики работы метода)
	function continueSearch($requestId) {
		return $this->soapClient->ContinueSearch((object)array("requestId" => $requestId));
	}

	// =========================================Методы актуализции туров===========================================

	// Метод актуализации предложения от туроператора
	function actualizePrice($operatorId, $tourId, $requestId) {
		return $this->soapClient->ActualizePrice((object)array("sourceId" => $operatorId, "offerId" => $tourId, "requestId" => $requestId)); 
	}

	// Метод актуализации, принимающий код тура с сайта sletat или трёхзначный код из метода актуализации
	// Подробнее - страница 31-32 документации
	function actualizePriceByCode($code) {
		return $this->soapClient->ActualizePriceByCode((object)array("code" => $code)); 
	}

	// Добавление заказа тура в систему Sletat
	function saveTourOrder($requestId, $tourId, $operatorId, $user, $email, $phone, $info) {
		return $this->soapClient->SaveTourOrder((object)array("requestId" => $requestId, "offerId" => $tourId, "sourceId" =>
			$operatorId, "user" => $user, "email" => $email, "phone" => $phone, "info" => $info));
	}

	// ======================================Методы получения данных об отелях=====================================

	// Метод получения полной информации об отеле
	function getHotelInformation($hotelId, $cssStyleSheet) {
		return $this->soapClient->GetHotelInformation((object)array("hotelId" => $hotelId, "cssStylesheet" => $cssStyleSheet));
	}
}

?>