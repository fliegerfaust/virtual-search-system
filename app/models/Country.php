<?php

class Country extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function depart_cities() {
		return $this->belongsToMany('DepartCity', 'depart_cities_countries', 'country_id', 'depcity_id');
	}

	public function resorts() {
		return $this->hasMany('Resort');
	}

	public function getDDateFromValue() {
		if ($this->attributes['d_date_from'] == 0) {
			$vtConstant = VTConstant::where(array("name" => $this->attributes['id']."_d_date_from"))->first();
			return $this->checkIfExists($vtConstant);
		}
		return $this->attributes['d_date_from'];
	}

	public function getDDateToValue() {
		if ($this->attributes['d_date_to'] == 0) {
			$vtConstant = VTConstant::where(array("name" => $this->attributes['id']."_d_date_to"))->first();
			return $this->checkIfExists($vtConstant);
		}
		return $this->attributes['d_date_to'];
	}

	public function getDNightFromValue() {
		if ($this->attributes['d_night_from'] == 0) {
			$vtConstant = VTConstant::where(array("name" => $this->attributes['id']."_d_night_from"))->first();
			return $this->checkIfExists($vtConstant);
		}
		return $this->attributes['d_night_from'];
	}

	public function getDNightToValue() {
		if ($this->attributes['d_date_to'] == 0) {
			$vtConstant = VTConstant::where(array("name" => $this->attributes['id']."_d_date_to"))->first();
			return $this->checkIfExists($vtConstant);
		}
		return $this->attributes['d_night_to'];
	}

	public function checkIfExists($vtConstant) {
			if (isset($vtConstant)) {
				return $vtConstant->getConstValue();
			}
			return 0;
	}

	public $timestamps = false;
}
