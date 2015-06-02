<?php

/**
 * HotelInfoParamsFilter класс фильтра обработки параметров $hotelId, $cssStyleSheet
 * get-hotel-info?hotelId=...
 */
class HotelInfoParamsFilter {
	public function filter() {
		
 		// Создаём экземпляр класса логгера для журналирования некорректных параметров
	  	$fvtl = new FormattedVTLogger;
		$logger = $fvtl->makeFormattedVTLogger('VTLogger');

		$hotelId = Input::get('hotelId');

		if (preg_match("/^[1-9]\d*$/", $hotelId) == 0) {
			$logger->addRecord(400, 'Invalid parameter!', array("hotelId" => $hotelId), 2);
			App::abort(404, 'Page not found.');
		}

	  	if (Input::has('cssStyleSheet')) {
	  		$cssStyleSheet = Input::get('cssStyleSheet');
	  		if (preg_match("/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/", $cssStyleSheet) == 0) {
	  			$logger->addRecord(400, 'Invalid parameter!', array("cssStyleSheet" => $cssStyleSheet), 2);
	  			App::abort(404, 'Page not found.');
	  		}
	  		$result["cssStyleSheet"] = $cssStyleSheet;
	  	}

		$result["hotelId"] = $hotelId;

		$_REQUEST["hotelParams"] = $result;
	}
}