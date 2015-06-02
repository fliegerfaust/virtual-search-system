<?php

/**
 * GetMinPriceParamsFilter класс фильтра обработки парамеров из GET запроса со страницы поиска туров
 * create-search-request?depCityId=...&targetType=...&...
 */
class GetMinPriceParamsFilter {
 	public function filter() {
  
	    // Забираем все параметры из запроса	
	 	$depCityId = Input::get('depCityId');
	  	$targetType = Input::get('targetType');
	  	$targetId = Input::get('targetId');

	  	// Создаём экземпляр класса логгера для журналирования некорректных параметров
	  	$fvtl = new FormattedVTLogger;
		$logger = $fvtl->makeFormattedVTLogger('VTLogger');

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

		$result = array("depCityId" => $depCityId, "targetType" => $targetType, "targetId" => $targetId);

		$_REQUEST['tourParams'] = $result;
  }
}