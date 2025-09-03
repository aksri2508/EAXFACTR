<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// ROUTING FOR TRANSACTIONS SCREEN
// SALES AR INVOICE 
$route['company/sales_ar_invoice/saveSalesArInvoice']	 = "company/Sales_transcations/saveSalesTransactions";
$route['company/sales_ar_invoice/updateSalesArInvoice']  = "company/Sales_transcations/updateSalesTransactions";
$route['company/sales_ar_invoice/editSalesArInvoice']	 = "company/Sales_transcations/editSalesTransactions";
$route['company/sales_ar_invoice/getSalesArInvoiceList'] 	 = "company/Sales_transcations/getSalesTransactionsList";
$route['company/sales_ar_invoice/getAnalyticsDetails'] 	 = "company/Sales_transcations/getAnalyticsDetails";
$route['company/sales_ar_invoice/downloadExcel'] 		 = "company/Sales_transcations/downloadExcel";
$route['company/sales_ar_invoice/downloadInvoice/(:any)'] 	= "company/Sales_transcations/downloadInvoice";
$route['company/sales_ar_invoice/picklist/(:any)'] 		 	= "company/Sales_transcations/downloadPicklistInvoice";
$route['company/sales_ar_invoice/copySalesArInvoice'] 		= "company/Sales_transcations/copySalesTransactions";
$route['company/sales_ar_invoice/proceedApprovalActivityForDocument'] 	= "company/Sales_transcations/proceedApprovalActivityForDocument";


// AR DOWN PAYMENT INVOICE
$route['company/sales_ar_dp_invoice/saveSalesArDpInvoice']	 = "company/Sales_transcations/saveSalesTransactions";
$route['company/sales_ar_dp_invoice/updateSalesArDpInvoice']   = "company/Sales_transcations/updateSalesTransactions";
$route['company/sales_ar_dp_invoice/editSalesArDpInvoice']	 = "company/Sales_transcations/editSalesTransactions";
$route['company/sales_ar_dp_invoice/getSalesArDpInvoiceList'] 	 = "company/Sales_transcations/getSalesTransactionsList";
$route['company/sales_ar_dp_invoice/getAnalyticsDetails'] 	 = "company/Sales_transcations/getAnalyticsDetails";
$route['company/sales_ar_dp_invoice/downloadExcel'] 		 = "company/Sales_transcations/downloadExcel";
$route['company/sales_ar_dp_invoice/downloadInvoice/(:any)'] 		 = "company/Sales_transcations/downloadInvoice";
$route['company/sales_ar_dp_invoice/copySalesArDpInvoice'] 		= "company/Sales_transcations/copySalesTransactions";
$route['company/sales_ar_dp_invoice/proceedApprovalActivityForDocument'] 	= "company/Sales_transcations/proceedApprovalActivityForDocument";

// AR CREDIT MEMO 
$route['company/sales_ar_credit_memo/saveSalesArCreditMemo']	 = "company/Sales_transcations/saveSalesTransactions";
$route['company/sales_ar_credit_memo/updateSalesArCreditMemo']   = "company/Sales_transcations/updateSalesTransactions";
$route['company/sales_ar_credit_memo/editSalesArCreditMemo']	 = "company/Sales_transcations/editSalesTransactions";
$route['company/sales_ar_credit_memo/getSalesArCreditMemoList'] 	 = "company/Sales_transcations/getSalesTransactionsList";
$route['company/sales_ar_credit_memo/getAnalyticsDetails'] 	 = "company/Sales_transcations/getAnalyticsDetails";
$route['company/sales_ar_credit_memo/downloadExcel'] 		 = "company/Sales_transcations/downloadExcel";
$route['company/sales_ar_credit_memo/downloadInvoice/(:any)'] 		 = "company/Sales_transcations/downloadInvoice";
$route['company/sales_ar_credit_memo/copySalesArCreditMemo'] 		= "company/Sales_transcations/copySalesTransactions";
$route['company/sales_ar_credit_memo/proceedApprovalActivityForDocument'] 	= "company/Sales_transcations/proceedApprovalActivityForDocument";

// SALES RETURN 
$route['company/sales_return/saveSalesReturn']		  = "company/Sales_transcations/saveSalesTransactions";
$route['company/sales_return/updateSalesReturn']  	  = "company/Sales_transcations/updateSalesTransactions";
$route['company/sales_return/editSalesReturn']	 	  = "company/Sales_transcations/editSalesTransactions";
$route['company/sales_return/getSalesReturnList'] 	  = "company/Sales_transcations/getSalesTransactionsList";
$route['company/sales_return/getAnalyticsDetails'] 	  = "company/Sales_transcations/getAnalyticsDetails";
$route['company/sales_return/downloadExcel'] 		  = "company/Sales_transcations/downloadExcel";
$route['company/sales_return/downloadInvoice/(:any)'] = "company/Sales_transcations/downloadInvoice";
$route['company/sales_return/copySalesReturn'] 	  = "company/Sales_transcations/copySalesTransactions";
$route['company/sales_return/proceedApprovalActivityForDocument'] 	= "company/Sales_transcations/proceedApprovalActivityForDocument";

// RENTAL QUOTE
$route['company/rental_quote/saveRentalQuote']	 		= "company/Rental_transcations/saveRentalTransactions";
$route['company/rental_quote/updateRentalQuote']  		= "company/Rental_transcations/updateRentalTransactions";
$route['company/rental_quote/editRentalQuote']	 		= "company/Rental_transcations/editRentalTransactions";
$route['company/rental_quote/getRentalQuoteList'] 	 	= "company/Rental_transcations/getRentalTransactionsList";
$route['company/rental_quote/getAnalyticsDetails'] 	 	= "company/Rental_transcations/getAnalyticsDetails";
$route['company/rental_quote/copyRentalQuote']		 	= "company/Rental_transcations/copyRentalTransactions";
$route['company/rental_quote/downloadExcel'] 		 	= "company/Rental_transcations/downloadExcel";
$route['company/rental_quote/downloadInvoice/(:any)'] 	= "company/Rental_transcations/downloadInvoice";
$route['company/rental_quote/sendDocumentMail'] 	    = "company/Rental_transcations/sendDocumentMail";


// RENTAL ORDER 
$route['company/rental_order/saveRentalOrder']	 		= "company/Rental_transcations/saveRentalTransactions";
$route['company/rental_order/updateRentalOrder']  		= "company/Rental_transcations/updateRentalTransactions";
$route['company/rental_order/editRentalOrder']	 		= "company/Rental_transcations/editRentalTransactions";
$route['company/rental_order/getRentalOrderList'] 	 	= "company/Rental_transcations/getRentalTransactionsList";
$route['company/rental_order/getAnalyticsDetails'] 	 	= "company/Rental_transcations/getAnalyticsDetails";
$route['company/rental_order/copyRentalOrder']		 	= "company/Rental_transcations/copyRentalTransactions";
$route['company/rental_order/downloadExcel'] 		 	= "company/Rental_transcations/downloadExcel";
$route['company/rental_order/downloadInvoice/(:any)'] 	= "company/Rental_transcations/downloadInvoice";
$route['company/rental_order/sendDocumentMail'] 	= "company/Rental_transcations/sendDocumentMail";

// RENTAL DELIVERY
$route['company/rental_delivery/saveRentalDelivery']	 	= "company/Rental_transcations/saveRentalTransactions";
$route['company/rental_delivery/updateRentalDelivery']  	= "company/Rental_transcations/updateRentalTransactions";
$route['company/rental_delivery/editRentalDelivery']	 	= "company/Rental_transcations/editRentalTransactions";
$route['company/rental_delivery/getRentalDeliveryList'] 	= "company/Rental_transcations/getRentalTransactionsList";
$route['company/rental_delivery/getAnalyticsDetails'] 	 	= "company/Rental_transcations/getAnalyticsDetails";
$route['company/rental_delivery/copyRentalDelivery']		 	= "company/Rental_transcations/copyRentalTransactions";
$route['company/rental_delivery/downloadExcel'] 		 	= "company/Rental_transcations/downloadExcel";
$route['company/rental_delivery/downloadInvoice/(:any)'] 	= "company/Rental_transcations/downloadInvoice";
$route['company/rental_delivery/sendDocumentMail'] 	        = "company/Rental_transcations/sendDocumentMail";



// RENTAL RETURN
$route['company/rental_return/saveRentalReturn']	 		= "company/Rental_transcations/saveRentalTransactions";
$route['company/rental_return/updateRentalReturn']  		= "company/Rental_transcations/updateRentalTransactions";
$route['company/rental_return/editRentalReturn']	 		= "company/Rental_transcations/editRentalTransactions";
$route['company/rental_return/getRentalReturnList'] 	 	= "company/Rental_transcations/getRentalTransactionsList";
$route['company/rental_return/getAnalyticsDetails'] 	 	= "company/Rental_transcations/getAnalyticsDetails";
$route['company/rental_return/copyRentalReturn']		 	= "company/Rental_transcations/copyRentalTransactions";
$route['company/rental_return/downloadExcel'] 		 	= "company/Rental_transcations/downloadExcel";
$route['company/rental_return/downloadInvoice/(:any)'] 	= "company/Rental_transcations/downloadInvoice";
$route['company/rental_return/sendDocumentMail'] 	        = "company/Rental_transcations/sendDocumentMail";


// RENTAL INVOICE
$route['company/rental_invoice/saveRentalInvoice']	 		= "company/Rental_transcations/saveRentalTransactions";
$route['company/rental_invoice/updateRentalInvoice']  		= "company/Rental_transcations/updateRentalTransactions";
$route['company/rental_invoice/editRentalInvoice']	 		= "company/Rental_transcations/editRentalTransactions";
$route['company/rental_invoice/getRentalInvoiceList'] 	 	= "company/Rental_transcations/getRentalTransactionsList";
$route['company/rental_invoice/getAnalyticsDetails'] 	 	= "company/Rental_transcations/getAnalyticsDetails";
$route['company/rental_invoice/copyRentalInvoice']		 	= "company/Rental_transcations/copyRentalTransactions";
$route['company/rental_invoice/downloadExcel'] 		 	    = "company/Rental_transcations/downloadExcel";
$route['company/rental_invoice/downloadInvoice/(:any)'] 	= "company/Rental_transcations/downloadInvoice";
$route['company/rental_invoice/sendDocumentMail'] 	        = "company/Rental_transcations/sendDocumentMail";

// RENTAL INSPECTION IN
$route['company/rental_inspection_in/saveRentalInspectionIn']	 		= "company/Rental_Inspection_transcations/saveRentalInspectionTransactions";
$route['company/rental_inspection_in/updateRentalInspectionIn']  		= "company/Rental_Inspection_transcations/updateRentalInspectionTransactions";
$route['company/rental_inspection_in/editRentalInspectionIn']	 		= "company/Rental_Inspection_transcations/editRentalInspectionTransactions";
$route['company/rental_inspection_in/getRentalInspectionInList'] 	 	= "company/Rental_Inspection_transcations/getRentalInspectionTransactionsList";

$route['company/rental_inspection_in/copyRentalInceptionIn']		 	= "company/Rental_Inspection_transcations/copyRentalInspectionTransactions";
$route['company/rental_inspection_in/getAnalyticsDetails'] 	 			= "company/Rental_Inspection_transcations/getAnalyticsDetails";
$route['company/rental_inspection_in/downloadExcel'] 		 			= "company/Rental_Inspection_transcations/downloadExcel";
$route['company/rental_inspection_in/downloadInvoice/(:any)'] 			= "company/Rental_Inspection_transcations/downloadInvoice";
$route['company/rental_inspection_in/sendDocumentMail'] 	            = "company/Rental_Inspection_transcations/sendDocumentMail";


// RENTAL INSPECTION OUT
$route['company/rental_inspection_out/saveRentalInspectionOut']	 		= "company/Rental_Inspection_transcations/saveRentalInspectionTransactions";
$route['company/rental_inspection_out/updateRentalInspectionOut']  		= "company/Rental_Inspection_transcations/updateRentalInspectionTransactions";
$route['company/rental_inspection_out/editRentalInspectionOut']	 		= "company/Rental_Inspection_transcations/editRentalInspectionTransactions";
$route['company/rental_inspection_out/getRentalInspectionOutList'] 	 	= "company/Rental_Inspection_transcations/getRentalInspectionTransactionsList";
$route['company/rental_inspection_out/copyRentalInceptionOut'] 	 		= "company/Rental_Inspection_transcations/copyRentalInspectionTransactions";
$route['company/rental_inspection_out/getAnalyticsDetails'] 	 		= "company/Rental_Inspection_transcations/getAnalyticsDetails";
$route['company/rental_inspection_out/downloadExcel'] 		 			= "company/Rental_Inspection_transcations/downloadExcel";
$route['company/rental_inspection_out/downloadInvoice/(:any)'] 			= "company/Rental_Inspection_transcations/downloadInvoice";
$route['company/rental_inspection_out/sendDocumentMail'] 	            = "company/Rental_Inspection_transcations/sendDocumentMail";



// For Mail Routings - Sales Module
$route['common/sendMail/2'] 		 = "company/sales_quote/sendDocumentMail";
$route['common/sendMail/3'] 		 = "company/sales_order/sendDocumentMail";
$route['common/sendMail/10'] 		 = "company/sales_delivery/sendDocumentMail";

// For Mail Routings - Purchase Module
$route['common/sendMail/5'] 		 = "company/purchase_request/sendDocumentMail";
$route['common/sendMail/6'] 		 = "company/purchase_order/sendDocumentMail";
$route['common/sendMail/7'] 		 = "company/grpo/sendDocumentMail";

$route['common/sendMail/8'] 		 = "company/inventory_transfer_request/sendDocumentMail";
$route['common/sendMail/9'] 		 = "company/inventory_transfer/sendDocumentMail";

// For Mail Routings - Sales Transaction Module
$route['common/sendMail/11'] 		 = "company/sales_transcations/sendDocumentMail";
$route['common/sendMail/12'] 		 = "company/sales_transcations/sendDocumentMail";
$route['common/sendMail/13'] 		 = "company/sales_transcations/sendDocumentMail";
$route['common/sendMail/14'] 		 = "company/sales_transcations/sendDocumentMail";

// For Mail Routings - RENTAL MODULE
$route['common/sendMail/18'] 		 = "company/rental_transcations/sendDocumentMail";
$route['common/sendMail/19'] 		 = "company/rental_transcations/sendDocumentMail";
$route['common/sendMail/21'] 		 = "company/rental_transcations/sendDocumentMail";
$route['common/sendMail/22'] 		 = "company/rental_transcations/sendDocumentMail";
$route['common/sendMail/24'] 		 = "company/rental_transcations/sendDocumentMail";


// RENTAL INSPECTION CONTROLLER 
//$route['common/sendMail/20'] 		 = "company/rental_Inspection_transcations/sendDocumentMail";
//$route['common/sendMail/23'] 		 = "company/rental_Inspection_transcations/sendDocumentMail";


/*
// RENTAL WORKLOG ROUTING - SHEET 1 
$route['company/rental_worklog/1/saveRentalWorklog']	 	= "company/rental_worklog/saveRentalWorklog";
$route['company/rental_worklog/1/editRentalWorklog']  		= "company/rental_worklog/editRentalWorklog";
$route['company/rental_worklog/1/updateRentalWorklog']	 	= "company/rental_worklog/updateRentalWorklog";
$route['company/rental_worklog/1/getRentalWorklogList'] 	= "company/rental_worklog/getRentalWorklogList";
$route['company/rental_worklog/1/getAnalyticsDetails'] 	 	= "company/rental_worklog/getAnalyticsDetails";
$route['company/rental_worklog/1/uploadWorklogTemplate']	= "company/rental_worklog/uploadWorklogTemplate";
$route['company/rental_worklog/1/downloadInvoice'] 		 	= "company/rental_worklog/downloadInvoice";
$route['company/rental_worklog/1/sendDocumentMail'] 	    = "company/rental_worklog/sendDocumentMail";
$route['company/rental_worklog/1/downloadExcel'] 		 	= "company/rental_worklog/downloadExcel";

// RENTAL WORKLOG ROUTING - SHEET 2
$route['company/rental_worklog/2/saveRentalWorklog']	 	= "company/rental_worklog_type_2/saveRentalWorklog";
$route['company/rental_worklog/2/editRentalWorklog']  		= "company/rental_worklog_type_2/editRentalWorklog";
$route['company/rental_worklog/2/updateRentalWorklog']	 	= "company/rental_worklog_type_2/updateRentalWorklog";

// USE FIRST SHEET END POINT ITSELF 
$route['company/rental_worklog/2/getRentalWorklogList'] 	= "company/rental_worklog/getRentalWorklogList";
$route['company/rental_worklog/2/getAnalyticsDetails'] 	 	= "company/rental_worklog/getAnalyticsDetails";
$route['company/rental_worklog/2/downloadExcel'] 		 	= "company/rental_worklog/downloadExcel";
$route['company/rental_worklog/2/uploadWorklogTemplate']	= "company/rental_worklog/uploadWorklogTemplate";
$route['company/rental_worklog/2/downloadInvoice'] 		 	= "company/rental_worklog/downloadInvoice";
$route['company/rental_worklog/2/sendDocumentMail'] 	    = "company/rental_worklog/sendDocumentMail";
*/

// For Mail Routings- Common Mails.
$route['common/sendMail'] 		 = "common/common_services/sendMail";


/* END OF FILE */