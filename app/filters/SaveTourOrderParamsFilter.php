<?php

/**
 * SaveTourOrderParamsFilter класс фильтра обработки парамеров из GET запроса со страницы заказа тура
 * save-tour-order?requestId=...&offerId=...&...
 */
class SaveTourOrderParamsFilter {
	public function filter() {

		// Создаём экземпляр класса логгера для журналирования некорректных параметров
	  	$fvtl = new FormattedVTLogger;
		$logger = $fvtl->makeFormattedVTLogger('VTLogger');
  
	    // Забираем все параметры из запроса	
	 	$requestId = Input::get('requestId');
	  	$offerId = Input::get('offerId');
	  	$sourceId = Input::get('sourceId');
	  	$username = Input::get('username');
	  	$email = Input::get('email');
	  	$phone = Input::get('phone');

	  	if (Input::has('info')) {
	  		$info = strip_tags(Input::get('info'));
	  		$result["info"] = $info;
	  	}

		if (preg_match("/^[1-9]\d*$/", $requestId) == 0) {
			$logger->addRecord(400, 'Invalid parameter!', array("requestId" => $requestId), 2);
			App::abort(404, 'Page not found.');
		}

		if (preg_match("/^[1-9]\d*$/", $offerId) == 0) {
			$logger->addRecord(400, 'Invalid parameter!', array("offerId" => $offerId), 2);
			App::abort(404, 'Page not found.');
		}

		if (preg_match("/^[1-9]\d*$/", $sourceId) == 0) {
			$logger->addRecord(400, 'Invalid parameter!', array("sourceId" => $sourceId), 2);
			App::abort(404, 'Page not found.');
		}

		if (preg_match("#^[Ёё0-9A-Za-zА-Яа-я_@\s]+$#ui", $username) == 0) {
			$logger->addRecord(400, 'Invalid parameter!', array("username" => $username), 2);
			App::abort(404, 'Page not found.');
		}

		if (preg_match("/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/", $email) == 0) {
			$logger->addRecord(400, 'Invalid parameter!', array("email" => $email), 2);
			App::abort(404, 'Page not found.');
		}		

		if (preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $phone) == 0) {
			$logger->addRecord(400, 'Invalid parameter!', array("phone" => $phone), 2);
			App::abort(404, 'Page not found.');
		}

		$result = array("requestId" => $requestId, "offerId" => $offerId, "sourceId" => $sourceId,
			"username" => $username, "email" => $email, "phone" => $phone);

		$_REQUEST["tourParams"] = $result;
	}
}