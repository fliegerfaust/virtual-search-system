<?php 

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Countries',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'Country',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Country',

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
    	'name' => array(
        	'title' => 'Name',
        	'type' => 'text'
    	),
    	'depart_cities' => array(
    		'title' => 'Depart Cities',
    		'type' => 'relationship',
    		'name_field' => 'name',
    		'constraints' => array('depart_city' => 'countries')
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