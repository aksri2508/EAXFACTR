<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*-------------------------------------------------------------------------------*/
// GENERIC CONFIGURATION SETTINGS 
/*-------------------------------------------------------------------------------*/
define('MAILFLAG',                1); //0 -> OFF ,1 -> ON
define('ENABLE_HTTP_STATUS_CODE', 0); //0 -> OFF ,1 -> ON


/* DOCUMENT ROOT PATH AND SERVER PATH DETAILS	*/
$doc_root 		= $_SERVER['DOCUMENT_ROOT'];
$domain_name = "";
if(isset($_SERVER['SERVER_NAME'])){
    $domain_name  	= "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
}


// FOR MAIL DETAILS 

//define('SERVER_IP','http://sapservice.castleready.com');
define('SERVER_IP','http://localhost');

define('WEB_SERVER_URL',SERVER_IP.":10010#/");
define('MAILIMGURL',SERVER_IP.':10010/mailtemplates/en/v1/');
define('MAILER_TEMPLATE_FILE',FCPATH."mailtemplates/USER_LANGUAGE/v1/index.php");
define('INVOICE_TEMPLATE_FILE',FCPATH."invoicetemplates/en/v1/index.html");
define('GATEPASS_BARCODE_TEMPLATE_FILE',FCPATH."gatepasstemplates/en/v1/index.html");
define('GATEPASS_BARCODE_GENERATION_PATH',FCPATH."temp/gatepass_barcode/");

define('FILE_DOWNLOAD_TEMPLATE_FILE',FCPATH."invoicetemplates/en/v1/rental_txn.html");
define('INVOICE_GENERATION_PATH',FCPATH."temp/invoice/");
define('INVOICE_MAIL_GENERATION_PATH',FCPATH."temp/mail_attachments/");

define('INVOICE_ACCESS_URL',$domain_name."/sap-backend/sap/temp/invoice/");
define('GATEPASS_BARCODE_ACCESS_URL',$domain_name."/temp/gatepass_barcode/");


define('TEMP_EXCEL_SAVE_PATH',APPPATH.'../temp/other_documents/');
//define('EXCEL_DOWNLOAD_PATH',SERVER_IP.'/temp/other_documents/');
define('SECURE_FILE_DOWNLOAD_URL',SERVER_IP.':8090/sap-backend/sap/common/common_services/downloadsecfile/');
define('SECURE_UPLOAD_PATH',APPPATH.'../../uploads/');
define('PRIVATE_FILE_DOWNLOAD_URL',SERVER_IP.'/common/common_services/downloadPrivatefile/');


//////////////////////////////////////// PASSWORD DETAILS ////////////////////////////////////////
define('SECRET_PASSWORD','abcdefgh');
define('TRACK_TRANSACTION_FLAG','1'); 	// 0 - OFF | 1 - ON
define('TRACK_API_FLAG','1'); 	// 0 - OFF | 1 - ON


// ORGANIZATION DATABASE AND ITS TABLE INFO 
define('ORG_DATABASE_NAME','xfactr_organization');
define('ORG_TBL_COMPANY_INFO','tbl_company_info');
define('ORG_TBL_ORGANIZATION_DETAILS','tbl_organization_details');


//////////////////////////////////////// TABLE DEFINITION ////////////////////////////////////////

define('LOGIN','tbl_login');
define('EMPLOYEE_PROFILE','tbl_employee_profile');

define('MASTER_INDUSTRY','tbl_master_industry');
define('MASTER_REASON','tbl_master_reason');
define('MASTER_STAGE','tbl_master_stages');
define('MASTER_INFORMATION_SOURCE','tbl_master_information_source');
define('MASTER_CURRENCY','tbl_master_currency');
define('MASTER_COMPETITOR','tbl_master_competitor');
define('MASTER_ITEM_GROUP','tbl_master_item_group');
define('MASTER_ITEM','tbl_master_item');
define('MASTER_ACTIVITY','tbl_master_activity');
define('MASTER_LEVEL_OF_INTEREST','tbl_master_level_of_interest');
define('MASTER_PRIORITY','tbl_master_priority');
define('MASTER_UOM','tbl_master_uom');
define('MASTER_TAX','tbl_master_tax');
define('MASTER_TAX_ATTRIBUTE','tbl_master_tax_attribute');
define('OPPORTUNITY','tbl_opportunity');
define('OPPORTUNITY_STAGES','tbl_opportunity_stages');
define('BUSINESS_PARTNER','tbl_business_partner');
define('BP_CONTACTS','tbl_bp_contacts');
define('BP_ADDRESS','tbl_bp_address');
define('MASTER_STATIC_DATA','tbl_master_static_data');
define('MASTER_BRANCHES','tbl_master_branches');
define('ACTIVITY','tbl_activity');
define('SALES_QUOTE','tbl_sales_quote');
define('SALES_QUOTE_ITEMS','tbl_sales_quote_items');
define('SALES_ORDER','tbl_sales_order');
define('SALES_ORDER_ITEMS','tbl_sales_order_items');
define('COMPANY_DETAILS','tbl_company_details');
define('MASTER_COUNTRY','tbl_master_country');
define('MASTER_STATE','tbl_master_state');
define('MASTER_COST_CENTER','tbl_master_cost_center');
define('MASTER_DIMENSION','tbl_master_dimension');
define('MASTER_TERRITORY','tbl_master_territory');
define('MASTER_OPPORTUNITY_TYPE','tbl_master_opportunity_type');
define('MASTER_BIN','tbl_master_bin');
define('MASTER_LOCATION','tbl_master_location');
define('MASTER_HSN','tbl_master_hsn');
define('MASTER_MANUFACTURER','tbl_master_manufacturer');
define('ATTACHMENTS','tbl_attachments');
define('MASTER_DISTRIBUTION_RULES','tbl_master_distribution_rules');
define('MASTER_PAYMENT_TERMS','tbl_master_payment_terms');
define('MASTER_PAYMENT_METHODS','tbl_master_payment_methods');
define('WAREHOUSE','tbl_warehouse');
define('MASTER_PRICE_LIST','tbl_master_price_list');
define('MASTER_DESIGNATION','tbl_master_designation');


define('PURCHASE_REQUEST','tbl_purchase_request');
define('PURCHASE_REQUEST_ITEMS','tbl_purchase_request_items');
define('PURCHASE_ORDER','tbl_purchase_order');
define('PURCHASE_ORDER_ITEMS','tbl_purchase_order_items');
define('GRPO','tbl_grpo');
define('GRPO_ITEMS','tbl_grpo_items');
define('ITEM_WAREHOUSE','tbl_item_warehouse');
define('INVENTORY_TRANSFER_REQUEST','tbl_inventory_transfer_request');
define('INVENTORY_TRANSFER_REQUEST_ITEMS','tbl_inventory_transfer_request_items');
define('INVENTORY_TRANSFER','tbl_inventory_transfer');
define('INVENTORY_TRANSFER_ITEMS','tbl_inventory_transfer_items');
define('SALES_DELIVERY','tbl_sales_delivery');
define('SALES_DELIVERY_ITEMS','tbl_sales_delivery_items');
define('SALES_AR_INVOICE','tbl_sales_ar_invoice');
define('SALES_AR_INVOICE_ITEMS','tbl_sales_ar_invoice_items');
define('SALES_AR_DP_INVOICE','tbl_sales_ar_dp_invoice');
define('SALES_AR_DP_INVOICE_ITEMS','tbl_sales_ar_dp_invoice_items');
define('SALES_AR_CREDIT_MEMO','tbl_sales_ar_credit_memo');
define('SALES_AR_CREDIT_MEMO_ITEMS','tbl_sales_ar_credit_memo_items');
define('SALES_RETURN','tbl_sales_return');
define('SALES_RETURN_ITEMS','tbl_sales_return_items');
define('ITEM_STOCKS','tbl_item_stocks');
define('TRACKING_TRANSACTION','tbl_tracking_transaction');
define('SETTINGS','tbl_settings');
define('MASTER_ISSUING_NOTE','tbl_master_issuing_note');
define('MASTER_ALTERNATIVE_ITEMS','tbl_master_alternative_items');
define('MASTER_ALTERNATIVE_ITEMS_LIST','tbl_master_alternative_items_list');
define('LINE_ITEM_CONFIGURATION','tbl_line_item_configuration');
define('USER_LINE_ITEM_CONFIGURATION','tbl_user_line_item_configuration');
define('SP_BUSINESS_PARTNER','tbl_sp_business_partner');
define('MASTER_ITEM_PRICE_LIST','tbl_master_item_price_list');
define('MASTER_DOCUMENT_NUMBERING','tbl_master_document_numbering');
define('MASTER_APPROVAL_STAGES','tbl_master_approval_stages');
define('APPROVAL_TEMPLATES','tbl_approval_templates');
define('APPROVAL_STATUS_REPORT','tbl_approval_status_report');
define('INDIVIDUAL_APPROVAL_STATUS_REPORT','tbl_individual_approval_status_report');
define('APPROVAL_TEMPLATES_VALIDATION','tbl_approval_templates_validation');


define('NOTIFICATIONS','tbl_notifications');
define('DEV_API_TRACKER','tbl_dev_api_tracker');
define('MASTER_TERMS_AND_CONDITION','tbl_master_terms_and_condition');

define('RENTAL_QUOTE','tbl_rental_quote');
define('RENTAL_QUOTE_ITEMS','tbl_rental_quote_items');
define('RENTAL_ORDER','tbl_rental_order');
define('RENTAL_ORDER_ITEMS','tbl_rental_order_items');
define('RENTAL_DELIVERY','tbl_rental_delivery');
define('RENTAL_DELIVERY_ITEMS','tbl_rental_delivery_items');
define('RENTAL_RETURN','tbl_rental_return');
define('RENTAL_RETURN_ITEMS','tbl_rental_return_items');
define('RENTAL_INSPECTION_IN','tbl_rental_inspection_in');
define('RENTAL_INSPECTION_IN_ITEMS','tbl_rental_inspection_in_items');
define('RENTAL_INSPECTION_OUT','tbl_rental_inspection_out');
define('RENTAL_INSPECTION_OUT_ITEMS','tbl_rental_inspection_out_items');
define('RENTAL_INVOICE','tbl_rental_invoice');
define('RENTAL_INVOICE_ITEMS','tbl_rental_invoice_items');
define('MASTER_RENTAL_EQUIPMENT','tbl_master_rental_equipment');
define('MASTER_RENTAL_EQUIPMENT_STATUS','tbl_master_rental_equipment_status');
define('MASTER_RENTAL_ITEM','tbl_master_rental_item');
define('RENTAL_WORKLOG','tbl_rental_worklog');
define('RENTAL_WORKLOG_ITEMS','tbl_rental_worklog_items');
define('RENTAL_WORKLOG_ITEMS_TYPE_2','tbl_rental_worklog_items_type_2');
define('MASTER_INSPECTION_TEMPLATE','tbl_master_inspection_template');
define('RENTAL_EQUIPMENT_SCREEN_CONFIGURATION','tbl_rental_equipment_screen_configuration');
define('MASTER_RENTAL_EQUIPMENT_CATEGORY','tbl_master_rental_equipment_category');



// UDF TABLES
define('UDF_MASTER_FIELD_TYPE','tbl_udf_master_field_type');
define('UDF_MASTER_FORM_CONTROLS','tbl_udf_master_form_controls');
define('UDF_MASTER_FORM_CONTROLS_OPTIONS','tbl_udf_master_form_controls_options');
define('UDF_SCREEN_MAPPING','tbl_udf_screen_mapping');


// SMT TEAM DETAILS 
define('SMT_TEAM','tbl_smt_team');
define('SMT_TEAM_MEMBERS','tbl_smt_team_members');
define('SMT_VISITS','tbl_smt_visits');
define('EMPLOYEE_ATTENDANCE','tbl_employee_attendance');

//////////////////////	  ATTACHMENTS - SECURE FOLDER /////////////////////////////
define('ATTACHMENTS_UPLOAD_PATH', SECURE_UPLOAD_PATH."/attachments/");
define('ATTACHMENTS_UPLOAD_SIZE', "8192");
define('ATTACHMENTS_UPLOAD_WIDTH', "1000");
define('ATTACHMENTS_UPLOAD_TYPES',"gif|jpg|png|PNG|jpeg|doc|docx|xls|xlsx|ppt|pptx|pdf|txt"); 

//////////////////////////////////////// PHOTO UPLOAD DETAILS ////////////////////////////////////////
// EMPLOYEE PHOTO  
define('EMPLOYEE_PHOTO_UPLOAD_PATH', $doc_root."/public/emp_images/");
define('EMPLOYEE_PHOTO_ACCESS_URL',$domain_name."/public/emp_images/");
define('EMPLOYEE_PHOTO_SIZE', "8192");
define('EMPLOYEE_PHOTO_WIDTH', "1000");
define('EMPLOYEE_PHOTO_TYPES',"gif|jpg|png|PNG|jpeg");
define('EMPLOYEE_PHOTO_SUCCESS',"Successfully  Uploaded.");
define('EMPLOYEE_PHOTO_ERROR',"Only gif | jpg | png | jpeg are allowed. Maximum width 200px");

// ITEM PHOTO  
define('ITEM_PHOTO_UPLOAD_PATH', $doc_root."/public/item_images/");
define('ITEM_PHOTO_ACCESS_URL',$domain_name."/public/item_images/");
define('ITEM_PHOTO_SIZE', "8192");
define('ITEM_PHOTO_WIDTH', "1000");
define('ITEM_PHOTO_TYPES',"gif|jpg|png|PNG|jpeg");
define('ITEM_PHOTO_SUCCESS',"Successfully  Uploaded.");
define('ITEM_PHOTO_ERROR',"Only gif | jpg | png | jpeg are allowed. Maximum width 200px");


// RENTAL ITEM PHOTO.  
define('RENTAL_ITEM_PHOTO_UPLOAD_PATH', $doc_root."/public/rental_item_images/");
define('RENTAL_ITEM_PHOTO_ACCESS_URL',$domain_name."/public/rental_item_images/");
define('RENTAL_ITEM_PHOTO_SIZE', "8192");
define('RENTAL_ITEM_PHOTO_WIDTH', "1000");
define('RENTAL_ITEM_PHOTO_TYPES',"gif|jpg|png|PNG|jpeg");
define('RENTAL_ITEM_PHOTO_SUCCESS',"Successfully  Uploaded.");
define('RENTAL_ITEM_PHOTO_ERROR',"Only gif | jpg | png | jpeg are allowed. Maximum width 200px");

// RENTAL EQUIPMENT PHOTO. 
define('RENTAL_EQUIPMENT_PHOTO_UPLOAD_PATH', $doc_root."/public/rental_equipment_images/");
define('RENTAL_EQUIPMENT_PHOTO_ACCESS_URL',$domain_name."/public/rental_equipment_images/");
define('RENTAL_EQUIPMENT_PHOTO_SIZE', "8192");
define('RENTAL_EQUIPMENT_PHOTO_WIDTH', "1000");
define('RENTAL_EQUIPMENT_PHOTO_TYPES',"gif|jpg|png|PNG|jpeg");
define('RENTAL_EQUIPMENT_PHOTO_SUCCESS',"Successfully  Uploaded.");
define('RENTAL_EQUIPMENT_PHOTO_ERROR',"Only gif | jpg | png | jpeg are allowed. Maximum width 200px");

// COMPANY LOGO  
define('COMPANY_LOGO_UPLOAD_PATH', $doc_root."/public/company_logo/");
//define('COMPANY_LOGO_ACCESS_URL',$domain_name."/public/company_logo/");
define('COMPANY_LOGO_ACCESS_URL',$domain_name."/sap-backend/sap/public/company_logo/");
define('COMPANY_LOGO_SIZE', "8192");
define('COMPANY_LOGO_WIDTH', "1000");
define('COMPANY_LOGO_TYPES',"gif|jpg|png|PNG|jpeg");
define('COMPANY_LOGO_SUCCESS',"Successfully  Uploaded.");
define('COMPANY_LOGO_ERROR',"Only gif | jpg | png | jpeg are allowed. Maximum width 200px");


// RENTAL ITEM PHOTO.  
define('WORKLOG_TEMPLATE_UPLOAD_PATH', $doc_root."/public/worklog_templates/");
define('WORKLOG_TEMPLATE_TYPES',"xls|xlsx");
define('WORKLOG_TEMPLATE_SUCCESS',"Successfully  Uploaded.");
define('WORKLOG_TEMPLATE_ERROR',"Only xls | xlsx are allowed.");


// NOTIFICATION  // 0 - OFF , 1 - ON 
define('WEB_NOTIFICATION',1); 
define('SMS_NOTIFICATION',1);
define('EMAIL_NOTIFICATION',1);


// ACCESS CONTROL MANAGEMENT.
define('MASTER_MODULE_SCREEN_MAPPING','tbl_master_module_screen_mapping');
define('ACCESS_CONTROL','tbl_access_control');
define('ACCESS_CONTROL_SCREEN_LIST','tbl_access_control_screen_list');
define('MASTER_MODULE','tbl_master_module');
define('MASTER_ROUTING_URL','tbl_master_routing_url');
define('MASTER_SCREEN_ROUTING_URL_MAPPING','tbl_master_screen_routing_url_mapping');


// VEHICLE MANAGEMENT
define('MASTER_VEHICLE','tbl_master_vehicle');
define('TRANSPORT','tbl_transport');
define('TRANSPORT_INVOICES','tbl_transport_invoices');
define('GATEPASS_HISTORY','tbl_gatepass_history');




define('ENABLE_ACL_CHECK',1); 



/* END OF FILE */
