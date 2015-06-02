<?php

class VTConstant extends Eloquent {

	protected $table = 'vt_constants';

	protected $guarded = array();

	public static $rules = array();

	public $timestamps = false;

	public $incrementing = false;

	public function getValueByName($name) {
		$neededRecord = $this::where(array ('name' => $name))->first();
		if (!is_object($neededRecord) || !isset($neededRecord->Id)) {
			$value = '';
		} else {
			$value = $neededRecord->value;
		}
		return $value;
	}

	public function getConstValue() {
		$value = isset($this->attributes['value']) ? $this->attributes['value'] : 0;
		return $value;
	}
}
