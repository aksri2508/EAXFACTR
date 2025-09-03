<?php
$config['ITEM_WAREHOUSE']['columns_list']	= array(
	array(
		'display_name'		=> 'Id',
		'tbl_field_name' 	=> 'id',
		'field_key' 		=> 'id',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 0,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Item Id',
		'tbl_field_name' 	=> 'item_id',
		'field_key' 		=> 'itemId',
		'field_type' 	 	=> '',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 0,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Warehouse Id',
		'tbl_field_name' 	=> 'warehouse_id',
		'field_key' 		=> 'warehouseId',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 1,
		'create_flag'		=> 1,
		'edit_flag'		    => 1,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Bin Id',
		'tbl_field_name' 	=> 'bin_id',
		'field_key' 		=> 'binId',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 1,
		'create_flag'		=> 1,
		'edit_flag'		    => 1,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
		array(
			'display_name'		=> 'Sap Id',
			'tbl_field_name' 	=> 'sap_id',
			'field_key' 		=> 'sapId',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 1,
			'sort_flag'			=> 1,
			'excel_flag'		=> 1,
			'create_flag'		=> 1,
			'edit_flag'		    => 1,
			'update_flag'		=> 1,
			'field_validation'	=> array(
				'is_mandatory'   => 0,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
		 )
		),
		array(
			'display_name'		=> 'Posting Status',
			'tbl_field_name' 	=> 'posting_status',
			'field_key' 		=> 'postingStatus',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 1,
			'sort_flag'			=> 1,
			'excel_flag'		=> 0,
			'create_flag'		=> 1,
			'edit_flag'		    => 1,
			'update_flag'		=> 1,
			'field_validation'	=> array(
				'is_mandatory'   => 0,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
		 )
		),
		array(
			'display_name'		=> 'Sap Error',
			'tbl_field_name' 	=> 'sap_error',
			'field_key' 		=> 'sapError',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 1,
			'sort_flag'			=> 1,
			'excel_flag'		=> 0,
			'create_flag'		=> 1,
			'edit_flag'		    => 1,
			'update_flag'		=> 1,
			'field_validation'	=> array(
				'is_mandatory'   => 0,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
		 )
	)
);
?>