<?php

/**
 * TourParamsFilter класс фильтра обработки парамеров из GET запроса со страницы поиска туров
 * create-search-request?depCityId=...&targetType=...&...
 */
class TourParamsFilter {
 	public function filter() {

 		// Создаём экземпляр класса логгера для журналирования некорректных параметров
	  	$fvtl = new FormattedVTLogger;
		$logger = $fvtl->makeFormattedVTLogger('VTLogger');
  
	    // Забираем все параметры из запроса	
	 	$depCityId = Input::get('depCityId');
	  	$targetType = Input::get('targetType');
	  	$targetId = Input::get('targetId');
	  	$departDate = Input::get('departDate');
	  	$nightsNum = Input::get('nightsNum');
	  	$adults = Input::get('adults');
	  	$kids = Input::has('kids') ? explode(',', Input::get('kids')) : array();
	  	$hotelCategoryId = Input::get('hotelCategoryId');

	  	if (Input::has('deltaDateFrom')) {
	  		$deltaDateFrom = Input::get('deltaDateFrom');
	  		if (preg_match("/^\-?[1-9]\d*$/", $deltaDateFrom) == 0) {
	  			$logger->addRecord(400, 'Invalid parameter!', array("deltaDateFrom" => $deltaDateFrom), 2);
	  			App::abort(404, 'Page not found.');
	  		}
	  		$result["deltaDateFrom"] = $deltaDateFrom;
	  	}

	  	if (Input::has('deltaDateTo')) {
	  		$deltaDateTo = Input::get('deltaDateTo');
	  		if (preg_match("/^\-?[1-9]\d*$/", $deltaDateTo) == 0) {
	  			$logger->addRecord(400, 'Invalid parameter!', array("deltaDateTo" => $deltaDateTo), 2);
	  			App::abort(404, 'Page not found.');
	  		}
	  		$result["deltaDateTo"] = $deltaDateTo;
	  	}

	  	if (Input::has('deltaNightsFrom')) {
	  		$deltaNightsFrom = Input::get('deltaNightsFrom');
	  		if (preg_match("/^[1-9]\d*$/", $deltaNightsFrom) == 0) {
	  			$logger->addRecord(400, 'Invalid parameter!', array("deltaNightsFrom" => $deltaNightsFrom), 2);
	  			App::abort(404, 'Page not found.');
	  		}
	  		$result["deltaNightsFrom"] = $deltaNightsFrom;
	  	}

	  	if (Input::has('deltaNightsTo')) {
	  		$deltaNightsTo = Input::get('deltaNightsTo');
	  		if (preg_match("/^[1-9]\d*$/", $deltaNightsTo) == 0) {
	  			$logger->addRecord(400, 'Invalid parameter!', array("deltaNightsTo" => $deltaNightsTo), 2);
	  			App::abort(404, 'Page not found.');
	  		}
	  		$result["deltaNightsTo"] = $deltaNightsTo;
	  	}

	  	if (preg_match("/^[1-9]\d?$/", $depCityId) == 0) {
	  		$logger->addRecord(400, 'Invalid parameter!', array("depCityId" => $depCityId), 2);
			App::abort(404, 'Page not found.');
	  	}

	  	if (preg_match("/^[1-3]$/", $targetType) == 0) {
	  		$logger->addRecord(400, 'Invalid parameter!', array("targetType" => $targetType), 2);
	  		App::abort(404, 'Page not found.');
	  	}

	  	if (preg_match("/^[1-9]\d*$/", $targetId) == 0) {
	  		$logger->addRecord(400, 'Invalid parameter!', array("targetId" => $targetId), 2);
	  		App::abort(404, 'Page not found.');
	  	}

	  	if (preg_match("/^([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})$/", $departDate) == 0) {
	  		$logger->addRecord(400, 'Invalid parameter!', array("departDate" => $departDate), 2);
	  		App::abort(404, 'Page not found.');
	  	}

	  	if ((preg_match("/^[1-9]\d*$/", $nightsNum) == 0) || (intval($nightsNum) > 21)) {
	  		$logger->addRecord(400, 'Invalid parameter!', array("nightsNum" => $nightsNum), 2);
	  		App::abort(404, 'Page not found.');
	  	}

	  	if (preg_match("/^[1-4]$/", $adults) == 0) {
	  		$logger->addRecord(400, 'Invalid parameter!', array("adults" => $adults), 2);
	  		App::abort(404, 'Page not found.');
	  	}

	  	foreach ($kids as $kid) {
		  	if ((preg_match("/^[1-9]\d*$/", $kid) == 0) || (intval($kid) >= 18)) {
	  			$logger->addRecord(400, 'Invalid parameter!', array("kid" => $kid), 2);
		  		App::abort(404, 'Page not found.');
		  	}
	  	}

	  	if (preg_match("/^4\d\d$/", $hotelCategoryId) == 0) {
	  		$logger->addRecord(400, 'Invalid parameter!', array("hotelCategoryId" => $hotelCategoryId), 2);
	  		App::abort(404, 'Page not found.');
	  	}	  		  	

		$result = array("depCityId" => $depCityId, "targetType" => $targetType, "targetId" => $targetId, "departDate" => $departDate, 
			                "nightsNum" => $nightsNum, "adults" => $adults, "kids" => $kids, "hotelCategoryId" => $hotelCategoryId);

		$_REQUEST['tourParams'] = $result;
  }
}