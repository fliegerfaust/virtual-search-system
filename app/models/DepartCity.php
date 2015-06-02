<?php

class DepartCity extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function countries() {
		return $this->belongsToMany('Country', 'depart_cities_countries', 'depcity_id', 'country_id');
	}

	public $timestamps = false;

}
