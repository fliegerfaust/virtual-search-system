<?php

class SearchControllerTest extends TestCase {

	public function test()
	{
		$response = $this->action('GET', 'VTSearchController@tourSearcher');
		$response->getContent();
	}

}