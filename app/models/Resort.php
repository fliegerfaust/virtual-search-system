<?php

class Resort extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function country() {
		return $this->belongsTo('Country', 'country_id');
	}

	public function hotels() {
		return $this->hasMany('Hotel');
	}

	public function getDDateFromValue() {
		if ($this->attributes['d_date_from'] == 0) {
			$country = Country::with('resorts')->where(array("id" => $this->attributes['country_id']))->first();
			return $country->getDDateFromValue();
		}
		return $this->attributes['d_date_from'];
	}

	public function getDDateToValue() {
		if ($this->attributes['d_date_to'] == 0) {
			$country = Country::with('resorts')->where(array("id" => $this->attributes['country_id']))->first();
			return $country->getDDateToValue();
		}
		return $this->attributes['d_date_to'];
	}

	public function getDNightFromValue() {
		if ($this->attributes['d_night_from'] == 0) {
			$country = Country::with('resorts')->where(array("id" => $this->attributes['country_id']))->first();
			return $country->getDNightFromValue();
		}
		return $this->attributes['d_night_from'];
	}

	public function getDNightToValue() {
		if ($this->attributes['d_night_to'] == 0) {
			$country = Country::with('resorts')->where(array("id" => $this->attributes['country_id']))->first();
			return $country->getDNightToValue();
		}
		return $this->attributes['d_night_to'];
	}	

	public $timestamps = false;
}
