<?php 

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Hotels',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'Hotel',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Hotel',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id' => array(
			'title' => 'id'
		),
		'sletat_id' => array(
			'title' => 'sletat_id'
		),
		'resort_id' => array(
			'title' => 'resort_id'
		),
		'star_name' => array(
			'title' => 'star_name',
			'type' => 'text'
		),
		'rate' => array(
			'title' => 'rate'
		),
		'name' => array(
			'title' => 'Name',
			'type'=> 'text'
		),
		'priority' => array(
			'title' => 'Priority',
		),
		'stop' => array(
			'title' => 'Stop',
			'type' => 'bool'
		)
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
    	'resort' => array(
			'title' => 'Resort name',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'resort_id' => array(
			'title' => 'resort_id'
		),
    	'name' => array(
        	'title' => 'Name',
        	'type' => 'text'
    	),
    	'star_name' => array(
    		'title' => 'star_name',
    		'type' => 'text'
    	),
    	'rate' => array(
    		'title' => 'rate'
    	),
    	'priority' => array(
    		'title' => 'Priority'
    	),
    	'stop' => array(
    		'title' => 'Stop',
    		'type' => 'bool'
    	)
    ),

	/**
	 * The filter set
	 */
    'filters' => array(
		'id',
		'sletat_id',
		'resort_id',
		'resort' => array(
			'title' => 'Resort name',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'star_name' => array(
			'title' => 'Star name'
		),
		'rate' => array(
			'title' => 'Rate'
		),
		'name' => array(
			'title' => 'Name'
		),
		'priority' => array(
			'title' => 'Priority'
		),
		'stop' => array(
			'title' => 'Stop',
			'type' => 'bool'
		)
	),

    /**
     * The rule set
     */
    'rules' => array(
    	'sletat_id' => 'required|integer',
    	'resort_id' => 'required|integer',
    	'name' => 'required',
    	'star_name' => 'required',
    	'rate' => 'required|integer',
    	'priority' => 'integer',
    	'stop' => 'boolean'
    ),

    /**
     * The sort set
     */
	'sort' => array(
    	'field' => 'id',
    	'direction' => 'asc',
	),
);