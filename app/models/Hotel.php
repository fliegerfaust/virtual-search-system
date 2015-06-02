<?php

class Hotel extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function resort() {
		return $this->belongsTo('Resort', 'resort_id');
	}

	public function getDDateFromValue() {
		$resort = Resort::with('hotels')->where(array("id" => $this->attributes['resort_id']))->first();
		return $resort->getDDateFromValue();
	}

	public function getDDateToValue() {
		$resort = Resort::with('hotels')->where(array("id" => $this->attributes['resort_id']))->first();
		return $resort->getDDateToValue();
	}

	public function getDNightFromValue() {
		$resort = Resort::with('hotels')->where(array("id" => $this->attributes['resort_id']))->first();
		return $resort->getDNightFromValue();
	}

	public function getDNightToValue() {
		$resort = Resort::with('hotels')->where(array("id" => $this->attributes['resort_id']))->first();
		return $resort->getDNightToValue();
	}
	
	public $timestamps = false;
}
