<?php 

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Users',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'User',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'User',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id' => array(
			'title' => 'id',
		),
		'username'=> array(
			'title' => 'Username'
		),
		'email' => array(
			'title' => 'E-mail',
			'type'=> 'text'
		),
		'password' => array(
			'title' => 'Password',
			'type'=> 'password'
		),
		'remember_token',
		'created_at',
		'updated_at'
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'username'=> array(	
			'title' => 'Username'
		),
		'email' => array(
			'title' => 'E-mail',
			'type'=> 'text'
		),
		'password' => array(
			'title' => 'Password',
			'type'=> 'password'
		)
    ),

	/**
	 * The filter set
	 */
    'filters' => array(
		'username'=> array(	
			'title' => 'Username'
		),
		'email' => array(
			'title' => 'E-mail',
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
     * The rule set
     */
    'rules' => array(
    	'username' => 'required',
    	'email' => 'email',
    	'password' => 'required'
    ),

    /**
     * The sort set
     */
	'sort' => array(
    	'field' => 'id',
    	'direction' => 'asc',
	),
);