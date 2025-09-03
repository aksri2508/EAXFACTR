<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*------------------------------------------------------------*/
// CONFIGURATION KEYS AND SETTINGS
/*------------------------------------------------------------*/
// JWT key
$config['jwt_key']			  = 'jwt_key_value';
$config['identification_key'] = md5('1234567890123456');

//password key values
$config['password_hash']	= PASSWORD_DEFAULT;
$config['password_cost']	= 14;
$config['reset_password'] 	= array(
									'email_expiry_hours' => 24									
								);

// LOGIN TYPE ARRAY DETAILS 								
$config['login_category'] = array(
									'org'	  => 'ORG',
									'company' => 'COMPANY',
									'branch'  => 'BRANCH'
								);
								
// NO TOKEN CHECKING
$config['checkNoTokens'] =  array(
								'pages' =>
									array('login' => array('checkLogin','forgotPassword','getCompanyList','getBranchList','checkOrganizationExpiry'),
										  'Test_services' => array('fileUpload','sendmail'),
									),
								'common' =>
									array('common_services' => array('getCompanyList','downloadSecFile','downloadPrivatefile')
									),	
								// 'cronjob' =>
								// 	array('sales_quote' => array('getSalesQuoteList')
								// 	),						
								);
								
$config['sharedApiController'] = [
				'common' => [],
				'pages'  => [],
];


$config['HTTP_RESPONSE_CODES'] = array(
    200 => 200,
    201 => 201,
    400 => 400,
    403 => 403,
    409 => 409,
	498 => 498
);


// THREAT LEVEL
$config['threatLevel'] = array(
									1 => 'Low',
									2 => 'Medium',
									3 => 'High'
							  );


$config['DELIVERY_STATUS']  = array(
								1 => 'No',
								2	=> 'Yes',
);

// STATUS 
$config['commonStatus'] = array(
									1 => 'Active',2 => 'In-Active'
						 );

// EQUIPMENT RENTAL STATUS 
$config['equipmentRentalStatus'] = array(
												1 => 'AVAILABLE',
												2 => 'RESERVED',
												3 => 'DELIVERED',
												4 => 'RETURNED',
												5 => 'UNAVAILABLE',
										);
					 
// SCREEN NAME | ID ARE MAPPED DIRECTLY FROM MASTER STATIC DATA TABLE (FIELD=> DOCUMENT TYPE )
$config['SCREEN_NAMES'] =  array(
	'ACTIVITY' 			=> array(	
									"id"				=> 4,
									"tableName"			=> ACTIVITY,
									"childTableName"	=> '',
									
									"displayName"		 => 'Activity',
								),
	'OPPORTUNITY'		=> array(
									"id"				=> 1,
									"tableName"			=> OPPORTUNITY,
									"childTableName"	=>'',
									 
									"displayName"		=> 'Sales Opportunities',
								),
	'EMPLOYEE_PROFILE'	=> array(	
									"id"				=> 17,
									"tableName"			=> EMPLOYEE_PROFILE,
									"childTableName"	=> '',
									
									"displayName"		=> 'Employee Profile',
								),
	'BUSINESS_PARTNER'	=> array(
									"id"				=>16,
									"tableName"			=>BUSINESS_PARTNER,
									"childTableName"	=>'',
									
									"displayName"		=> 'Business Partner Master Data',
									),
	'MASTER_ITEM'		=> array(	
									"id"				=>15,
									"tableName"			=>MASTER_ITEM,
									"childTableName"	=>'',
									
									"displayName"		=> 'Item Master Data',
									),
	'SALES_QUOTE'		=> array(
									"id"				=> 2,
									"tableName"			=> SALES_QUOTE,
									"childTableName"	=> SALES_QUOTE_ITEMS,
									"childRefId"		=> "sales_quote_id",
									
									"displayName"		=> 'Sales Quote',
								 ),
	'SALES_ORDER' 		=> array(
								"id"				=> 3,
								 "tableName"		=> SALES_ORDER,
								 "childTableName"	=> SALES_ORDER_ITEMS,
								 "childRefId"		=> "sales_order_id",
								
								 "displayName"		=> 'Sales Order',
								 
								 ),	
	'PURCHASE_REQUEST'	=> array(
								  "id"				=> 5,
								  "tableName"		=> PURCHASE_REQUEST,
								  "childTableName"	=> PURCHASE_REQUEST_ITEMS,
								  "childRefId"		=> "purchase_request_id",
								 
								  "displayName"		=> 'Purchase Request',
								),
	'PURCHASE_ORDER'	=> array(
									"id"				=> 6,
									"tableName"			=> PURCHASE_ORDER,
									"childTableName"	=> PURCHASE_ORDER_ITEMS,
									"childRefId"		=> "purchase_order_id",
									
									"displayName"		=> 'Purchase Order',
								),
								
	'GRPO'				=> array( "id"			  => 7,
							      "tableName"	  => GRPO,
								  "childTableName"=> GRPO_ITEMS,
								  "childRefId"	  => "grpo_id",
								 
								  "displayName"				=> 'Goods Receipt PO',
								  ),

	'INVENTORY_TRANSFER_REQUEST' => array("id"			  => 8,
										  "tableName"	  => INVENTORY_TRANSFER_REQUEST,
										  "childTableName"=> INVENTORY_TRANSFER_REQUEST_ITEMS,
										  "childRefId"	  => "inventory_transfer_request_id",
										  "displayName"		=> 'Inventory transfer Request',
										  ),
	
	'INVENTORY_TRANSFER'		=> array("id"			  => 9,
										  "tableName"	  => INVENTORY_TRANSFER,
										  "childTableName"=> INVENTORY_TRANSFER_ITEMS,
										  "childRefId"	  => "inventory_transfer_id",
										  "displayName"		=> 'Inventory transfer',
									),
										  
	'SALES_DELIVERY'		=> array(	  "id"			  => 10,
										  "tableName"	  => SALES_DELIVERY,
										  "childTableName"=> SALES_DELIVERY_ITEMS,
										  "childRefId"	  => "sales_delivery_id",
										   "displayName"		=> 'Sales Delivery',
									),

	'SALES_AR_INVOICE'		=> array(	  "id"			  => 11,
										  "tableName"	  => SALES_AR_INVOICE,
										  "childTableName"=> SALES_AR_INVOICE_ITEMS,
										  "childRefId"	  => "sales_ar_invoice_id",
										  "MsgTitle" 	  => "Sales Ar Invoice",
										  "displayName"		=> 'AR Invoice',
									),
										  
	'SALES_AR_DP_INVOICE'		=> array( "id"			  => 12,
										  "tableName"	  => SALES_AR_DP_INVOICE,
										  "childTableName"=> SALES_AR_DP_INVOICE_ITEMS,
										  "childRefId"	  => "sales_ar_dp_invoice_id",
										  "MsgTitle" 	  => "Sales Ar Dp Invoice",
										  "displayName"		=> 'AR DownPayment Invoice',
									),
										  
	'SALES_AR_CREDIT_MEMO'		=> array( "id"			  => 13,
										  "tableName"	  => SALES_AR_CREDIT_MEMO,
										  "childTableName"=> SALES_AR_CREDIT_MEMO_ITEMS,
										  "childRefId"	  => "sales_ar_credit_memo_id",
										  "MsgTitle" 	  => "Sales Ar Credit Memo",
										  "displayName"		=> 'AR Credit Memo',
									),
										  
	'SALES_RETURN'				=> array(
										  "id"			  => 14,
										  "tableName"	  => SALES_RETURN,
										  "childTableName"=> SALES_RETURN_ITEMS,
										  "childRefId"	  => "sales_return_id",
										  "MsgTitle" 	  => "Sales Return",
										  "displayName"		=> 'Sales Return',
									),
	 'RENTAL_QUOTE' 			=> array(
	 									"id"			  => 18,
	 									"tableName"	 	  => RENTAL_QUOTE,
	 									"childTableName"  => RENTAL_QUOTE_ITEMS,
	 									"childRefId"	  => "rental_quote_id",
	 									"MsgTitle" 	  	  => "Rental Quote",
	 									"displayName"		=> 'Rental Quote',
	 							),
	 'RENTAL_ORDER' 			=> array(
	 								"id"			  	=> 19,
	 								"tableName"	  		=> RENTAL_ORDER,
	 								"childTableName"	=> RENTAL_ORDER_ITEMS,
	 								"childRefId"	 	=> "rental_order_id",
	 								"MsgTitle" 	  		=> "Rental Order",
	 								"displayName"		=> 'Rental Order',
	 						),
	 'RENTAL_INSPECTION_OUT' 		=> array(
	 								"id"			  	=> 20,
	 								"tableName"	  		=> RENTAL_INSPECTION_OUT,
	 								"childTableName"	=> RENTAL_INSPECTION_OUT_ITEMS,
	 								"childRefId"	  	=> "rental_inspection_out_id",
	 								"MsgTitle" 	  		=> "Inspection Out",
	 								"displayName"		=> 'Inspection Out',
	 							),						
	 'RENTAL_DELIVERY' 			=> array(
	 									"id"			  	=> 21,
	 									"tableName"	  		=> RENTAL_DELIVERY,
	 									"childTableName"	=> RENTAL_DELIVERY_ITEMS,
	 									"childRefId"	  	=> "rental_delivery_id",
	 									"MsgTitle" 	  		=> "Rental Delivery",
	 									"displayName"		=> 'Rental Delivery',
	 			),
	 'RENTAL_RETURN' 			=> array(
	 									"id"			  	=> 22,
	 									"tableName"	 	 	=> RENTAL_RETURN,
	 									"childTableName"	=> RENTAL_RETURN_ITEMS,
	 									"childRefId"	  	=> "rental_return_id",
	 									"MsgTitle" 	 	 	=> "Rental Return",
	 									"displayName"		=> 'Rental Return',
	 			),
	 'RENTAL_INSPECTION_IN' 		=> array(
	 								"id"			  	=> 23,
	 								"tableName"	  		=> RENTAL_INSPECTION_IN,
	 								"childTableName"	=> RENTAL_INSPECTION_IN_ITEMS,
	 								"childRefId"	  	=> "rental_inspection_in_id",
	 								"MsgTitle" 	  		=> "Inspection In",
	 								"displayName"		=> 'Inspection In',
	 							),
	 'RENTAL_INVOICE' 		=> array(
	 									"id"			  	=> 24,
	 									"tableName"	 	 	=> RENTAL_INVOICE,
	 									"childTableName"	=> RENTAL_INVOICE_ITEMS,
	 									"childRefId"	  	=> "rental_invoice_id",
	 									"MsgTitle" 	 	 	=> "Rental Invoice",
	 									"displayName"		=> 'Rental Invoice',
									),
	'RENTAL_WORKLOG' 		=> array(
	 									"id"			  	=> 25,
	 									"tableName"	 	 	=> RENTAL_WORKLOG,
	 									"childTableName"	=> RENTAL_WORKLOG_ITEMS,
	 									"childRefId"	  	=> "rental_worklog_id",
	 									"MsgTitle" 	 	 	=> "Worklog",
	 									"displayName"		=> 'Worklog',
								),
	'MASTER_RENTAL_EQUIPMENT'  => array(
	 									"id"			  	=> 26,
	 									"tableName"	 	 	=> MASTER_RENTAL_EQUIPMENT,
	 									"childTableName"	=> '',
	 									"childRefId"	  	=> "",
	 									"MsgTitle" 	 	 	=> "Rental equipment",
	 									"displayName"		=> 'Rental equipment',
	),
	'TRANSPORT'  => array(
	 									"id"			  	=> 27,
	 									"tableName"	 	 	=> TRANSPORT,
	 									"childTableName"	=> '',
	 									"childRefId"	  	=> "",
	 									"MsgTitle" 	 	 	=> "Transport",
	 									"displayName"		=> 'Transport',
	)
);
?>