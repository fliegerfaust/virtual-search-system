<?php 

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'VT Constants',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'VT Constant',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'VTConstant',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'name' => array(
			'title' => 'Name'
		),
		'value'=> array(	
			'title' => 'Value'
		),
		'desc' => array(
			'title' => 'Description',
			'type'=> 'text'
		)
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'name' => array(
			'title' => 'Name'
		),
		'value'=> array(	
			'title' => 'Value'
		),
		'desc' => array(
			'title' => 'Description',
			'type'=> 'text'
		)
    ),

	/**
	 * The filter set
	 */
    'filters' => array(
    	'id',
		'name' => array(
			'title' => 'Name'
		),
		'value'=> array(	
			'title' => 'Value'
		),
		'desc' => array(
			'title' => 'Description',
			'type'=> 'text'
		)
    ),

    /**
     * The rule set
     */
    'rules' => array(
    	'name' => 'required',
    	'value' => 'required|integer',
    	'desc' => 'required'
    ),

    /**
     * The sort set
     */
	'sort' => array(
    	'field' => 'id',
    	'direction' => 'asc',
	),
);