<?php

/**
* Контроллер для выдачи данных о странах и курортах на front end.
*/

class OutputDataController extends BaseController {

	/**
	 * [getData Получаем данные при вызове метода]
	 * @return array JSON
	 */
	public function getData() {
		$result = array(
			array(
				"id" => '', 
				"name" => '', 
				"resorts" => array(
					array(
						"id" => '', 
						"name" => ''
					)
				)
			)
		);

		$i = 0;
		$j = 0;
		$countries = Country::with('resorts')->get();
		foreach ($countries as $country) {
			$result[$i]["id"] = $country->id;
			$result[$i]["name"] = $country->name;
			foreach ($country->resorts()->get() as $resort) {
				$result[$i]["resorts"][$j]["id"] = $resort->id;
				$result[$i]["resorts"][$j]["name"] = $resort->name;
				$j++;
			}
			$i++;
		}

	$result = array("countries" => $result);
	return json_encode($result);
	}

}
