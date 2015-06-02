<?php 

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Depart cities',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'Depart city',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'DepartCity',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id' => array(
			'title' => 'id',
		),
		'sletat_id'=> array(	
			'title' => 'sletat_id'
		),
		'name' => array(
			'title' => 'Name',
			'type'=> 'text'
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
    	'name' => array(
        	'title' => 'Name',
        	'type' => 'text'
    	),
    	'countries' => array(
    		'title' => 'Countries',
    		'type' => 'relationship',
    		'name_field' => 'name',
    		'constraints' => array('country' => 'depart_cities')
    	)
    ),

	/**
	 * The filter set
	 */
    'filters' => array(
    	'id',
    	'sletat_id',
    	'name' => array(
    		'title' => 'Name'
    	)
    ),

    /**
     * The rule set
     */
    'rules' => array(
    	'sletat_id' => 'required|integer',
    	'name' => 'required'
    ),

    /**
     * The sort set
     */
    'sort' => array(
    	'field' => 'id',
    	'direction' => 'asc',
	),
);