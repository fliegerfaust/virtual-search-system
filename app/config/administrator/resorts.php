<?php 

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Resorts',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'Resort',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Resort',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id' => array(
			'title' => 'id'
		),
		'sletat_id'=> array(
			'title' => 'sletat_id'
		),
		'country_id' => array(
			'title' => 'country_id'
		),
		'name' => array(
			'title' => 'Name',
			'type'=> 'text'
		),
		'd_date_from',
		'd_date_to',
		'd_night_from',
		'd_night_to'
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
    	'id' => array(
    		'title' => 'id'
    	),
    	'sletat_id' => array(
    		'title' => 'sletat_id'
    	),
    	'country' => array(
			'title' => 'Country name',
			'type' => 'relationship',
			'name_field' => 'name'
		),
    	'country_id' => array(
			'title' => 'country_id'
		),
    	'name' => array(
        	'title' => 'Name',
        	'type' => 'text'
    	),
    	'd_date_from',
		'd_date_to',
		'd_night_from',
		'd_night_to'
    ),

	/**
	 * The filter set
	 */
    'filters' => array(
		'id',
		'sletat_id',
		'country' => array(
			'title' => 'Country name',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'country_id',
		'name' => array(
			'title' => 'Name'
		),
		'd_date_from',
		'd_date_to',
		'd_night_from',
		'd_night_to'
	),

    /**
     * The rule set
     */
    'rules' => array(
    	'sletat_id' => 'required|integer',
    	'country_id' => 'required|integer',
    	'name' => 'required',
    	'd_date_from' => 'integer',
    	'd_date_to' => 'integer',
    	'd_night_from' => 'integer',
    	'd_night_to' => 'integer'
    ),

    /**
     * The sort set
     */
    'sort' => array(
    	'field' => 'id',
    	'direction' => 'asc',
	),	
);