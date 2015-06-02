<?php 

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Permissions',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'Permission',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Permission',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id' => array(
			'title' => 'id'
		),
		'name' => array(
			'title' => 'Name',
			'type'=> 'text'
		),
		'display_name' => array(
			'title' => 'Display name',
			'type'=> 'text'
		),
		'created_at',
		'updated_at'
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'id' => array(
			'title' => 'id'
		),
		'name' => array(
			'title' => 'Name',
			'type'=> 'text'
		),
		'display_name' => array(
			'title' => 'Display name',
			'type'=> 'text'
		),
    ),

	/**
	 * The filter set
	 */
    'filters' => array(
		'id' => array(
			'title' => 'id'
		),
		'name' => array(
			'title' => 'Name',
			'type'=> 'text'
		),
		'display_name' => array(
			'title' => 'Display name',
			'type'=> 'text'
		),
		'created_at' => array(
			'type' => 'datetime',
		    'title' => 'Created at',
		    'date_format' => 'dd-mm-yy',
		    'time_format' => 'HH:mm',
		),
		'updated_at' => array(
			'type' => 'datetime',
		    'title' => 'Updated at',
		    'date_format' => 'dd-mm-yy',
		    'time_format' => 'HH:mm',
		),
	),

    /**
     * The sort set
     */
    'sort' => array(
    	'field' => 'id',
    	'direction' => 'asc',
	),	
);