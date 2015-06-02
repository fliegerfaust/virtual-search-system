<?php

/**
 * RequestIdFilter класс фильтра обработки параметра $requestId
 * get-tours-data?requestId=...
 */
class RequestIdFilter {
	public function filter() {

		$requestId = Input::get('requestId');

		if (preg_match("/^[1-9]\d*$/", $requestId) == 0) {

			// Журналируем некорректный параметр
			$fvtl = new FormattedVTLogger;
			$logger = $fvtl->makeFormattedVTLogger('VTLogger');
			$logger->addRecord(400, 'Invalid parameter!', array("requestId" => $requestId), 2);
			App::abort(404, 'Page not found.');
		}

		$_REQUEST['requestId'] = $requestId;
	}
}