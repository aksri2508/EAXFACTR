<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Purchase_transactions.php
* @Class  			 : Purchase_transactions
* Model Name         : Purchase_transactions_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 26 APRIL 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/


class Purchase_transactions extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('company/purchase_transactions_model', 'nativeModel');

		// Type based conditon -- start
		// Allowed values
		$this->stgList = array("request", "order", "grpo");

		$stgName = $this->currentRequestData['stgName'];
		if (in_array($stgName, $this->stgList)) {
			$stgName = $this->currentRequestData['stgName'];
			switch ($stgName) {
				case 'request':
					$this->respMsg['create'][1] = lang('MSG_172'); // create message
					$this->respMsg['create'][2] = lang('GLB_009'); // unable to save
					$this->respMsg['create'][4] = lang('MSG_173'); // already exist
					
					$this->respMsg['update'][1] = lang('MSG_174'); // create message
					$this->respMsg['update'][2] = lang('GLB_009'); // unable to save
					$this->respMsg['update'][4] = lang('MSG_173'); // already exist
					
					$this->respMsg['edit'][1] = lang('GLB_015'); // Invalid Id Passed
					$this->respMsg['edit'][2] = lang('GLB_07'); // Invalid Params
					// Invalid Id Passed
					$this->respMsg['delete'][1] = lang('MSG_175'); 
					// Unable to delete. Please try again later
					$this->respMsg['delete'][2] = lang('GLB_011');
					$this->respMsg['delete'][3] = lang('GLB_07'); // Invalid Params
					break;
				case 'order':
					$this->respMsg['create'][1] = lang('MSG_176'); // create message
					$this->respMsg['create'][2] = lang('GLB_009'); // unable to save
					$this->respMsg['create'][4] = lang('MSG_177'); // already exist

					$this->respMsg['update'][1] = lang('MSG_178'); // create message
					$this->respMsg['update'][2] = lang('GLB_009'); // unable to save
					$this->respMsg['update'][4] = lang('MSG_173'); // already exist

					$this->respMsg['edit'][1] = lang('GLB_015'); // Invalid Id Passed
					$this->respMsg['edit'][2] = lang('GLB_07'); // Invalid Params

					// $this->createMsg = lang('MSG_176');
					// $this->updateMsg = lang('MSG_178');
					// $this->alreadyExistMsg = lang('MSG_177');
					// $this->deleteMsg = lang('MSG_179');
					break;
				case 'grpo':
					$this->respMsg['create'][1] = lang('MSG_180'); // create message
					$this->respMsg['create'][2] = lang('GLB_009'); // unable to save
					$this->respMsg['create'][4] = lang('MSG_181'); // already exist

					$this->respMsg['update'][1] = lang('MSG_182'); // create message
					$this->respMsg['update'][2] = lang('GLB_009'); // unable to save
					$this->respMsg['update'][4] = lang('MSG_173'); // already exist

					$this->respMsg['edit'][1] = lang('GLB_015'); // Invalid Id Passed
					$this->respMsg['edit'][2] = lang('GLB_07'); // Invalid Params

					// $this->createMsg = lang('MSG_180');
					// $this->updateMsg = lang('MSG_182');
					// $this->alreadyExistMsg = lang('MSG_181');
					// $this->deleteMsg = lang('MSG_183');
					break;
			}
		} else {
			$outputData['status']  = "FAILURE";
			$outputData['message'] = "Invalid Type";
			echo json_encode($outputData);
			exit;
		}
		// Type based conditon -- end.
	}


	/**
	 * @METHOD NAME 	: saveSalesQuote()
	 *
	 * @DESC 		: TO SAVE THE SALES QUOTE DETAILS
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function savePurchase()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData = $this->currentRequestData;

		$modelOutput = $this->nativeModel->savePurchase($getData);
		$mof = $modelOutput['flag'];

		if (1 == $mof) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";

			$outputData['message']   =  $this->respMsg['create'][1];
			// Successfully Inserted
		} else {
			$outputData['message']  = $this->respMsg['create'][$mof];
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: saveSalesQuoteItems()
	 *
	 * @DESC 		: TO SAVE THE SALES QUOTE ITMES DETAILS 
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function savePurchaseItems()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		$modelOutput 	   = $this->nativeModel->saveSalesQuoteItems($getData, 1);
		$mof = $modelOutput['flag'];

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = $this->respMsg['create'][1];  // Successfully Inserted
		} else {
			$outputData['message']  = $this->respMsg['create'][$mof];
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: updateSalesQuote()
	 *
	 * @DESC 		: TO UPDATE THE SALES QUOTE 
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function updatePurchase()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updatePurchase($this->currentRequestData);
		$mof = $modelOutput['flag'];
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = $this->respMsg['update'][$mof]; //'Successfully updated
		} else {
			$outputData['message']  = $this->respMsg['update'][$mof];
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: updateSalesQuoteItems()
	 *
	 * @DESC 		: TO UPDATE THE SALES QUOTE ITEMS
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function updateSalesQuoteItems()
	{
		// Params from http request
		$this->checkRequestMethod("put"); // Check the Request Method

		$getData 		   = $this->currentRequestData;

		$modelOutput 	   = $this->nativeModel->updateSalesQuoteItems($getData, 1);

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_121');  // Sales Quote Items updated Successfully.
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_122');  // UNABLE TO UPDATE THE RECORD
		}

		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: editSalesQuote()
	 *
	 * @DESC 		: TO EDIT SALES QUOTE DETAILS 
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function editPurchase($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 			= $this->nativeModel->editPurchase($id);
			$getSalesQuoteItemList 	= $this->nativeModel->editPurchaseItemList($id);

			if (count($modelOutput) > 0) {

				// $modelOutput = $this->frameSalesQuoteEditDetails($modelOutput);

				$data 				  = array();
				$data				  = $modelOutput[0];

				// BIND THE LIST SUB ARRAY 
				// $getSalesQuoteItemList	= $this->frameSalesQuoteItem($getSalesQuoteItemList);
				$data['listArray'] 		= $getSalesQuoteItemList;
				$outputData['status']   = "SUCCESS";
				$outputData['results']  = $data;
			} else {
				$outputData['message'] =  lang('GLB_015');  // INVALID ID PASSED
			}
		} else {
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
		}
		$this->output->sendResponse($outputData);
	}

	/**
	 * @METHOD NAME 	: deleteSalesQuote()
	 *
	 * @DESC 		: TO DELETE THE SALES QUOTE
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function deletePurchase()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
			$modelOutput = $this->nativeModel->deletePurchase($this->currentRequestData);
			$mof = $modelOutput['flag'];
			if (1 == $modelOutput['flag']) {
				$outputData['status']  = "SUCCESS";
				 //'Successfully Deleted
				$outputData['message']  =  $this->respMsg['delete'][$mof]; //1
			}else {
				// Unable to delete. Please try again later.
				$outputData['message']  = $this->respMsg['delete'][$mof]; //2
			}
		} else {
			$outputData['message'] = $this->respMsg['delete'][3]; // Invalid Paremeters
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: deleteSalesQuoteItems()
	 *
	 * @DESC 		: TO DELETE THE SALES QUOTE ITEMS
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function deletePurchaseItems()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
			$modelOutput = $this->nativeModel->deletePurchaseItems($this->currentRequestData, 1);
			$mof = $modelOutput['flag'];
			if (1 == $modelOutput['flag']) {
				$outputData['status']  = "SUCCESS";
				 //'Successfully Deleted
				$outputData['message']  =  $this->respMsg['delete'][$mof]; //1
			}else {
				// Unable to delete. Please try again later.
				$outputData['message']  = $this->respMsg['delete'][$mof]; //2
			}

		} else {
			// Invalid Paremeters
			$outputData['message'] = $this->respMsg['delete'][3]; 
		}
		$this->output->sendResponse($outputData);
	}

	


	/**
	 * @METHOD NAME 	: frameSalesQuoteEditDetails()
	 *
	 * @DESC 		: TO FRAME THE SALES QUOTE EDIT DETAILS   
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function frameSalesQuoteEditDetails($modelOutput)
	{
		// PASS CURRENCY DATA 
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 0;
		$passBusinessPartnerData['id'] 	= $modelOutput[0]['business_partner_id'];
		$businessPartnerDetails   		= $this->commonModel->getBusinessPartnerAutoList(
			array_merge($passSearchData, $passBusinessPartnerData)
		);


		// GET BUSINESS PARTNER CONTACTS DETAILS  
		$passBusinessPartnerContactsData['id'] 	= $modelOutput[0]['business_partner_contacts_id'];
		$businessPartnerContactsDetails 		=
			$this->commonModel->getBusinessPartnerContactsAutoList(array_merge($passSearchData, $passBusinessPartnerContactsData));

		// EMPLOYEE INFORMATION
		$passEmployeeData['id'] 		= $modelOutput[0]['emp_id'];
		$employeeDetails   				= $this->commonModel->getEmployeeAutoList(
			array_merge($passSearchData, $passEmployeeData)
		);

		// STATUS LIST
		$passStatusData['type']  = 'SALES_QUOTE_STATUS';
		$passStatusData['id'] 	 = $modelOutput[0]['status'];
		$statusDetails  		 = $this->commonModel->getMasterStaticDataAutoList(2, array_merge($passSearchData, $passStatusData));

		// CURRENCY DETAILS 
		$passCurrencyData['id'] 	 	= $modelOutput[0]['currency_id'];
		$currencyDetails  		 		= $this->commonModel->getCurrencyAutoList(2, array_merge($passSearchData, $passCurrencyData));


		// FRAME EDIT DETAILS			
		$modelOutput[0]['currencyInfo'] 				= $currencyDetails;
		$modelOutput[0]['businessPartnerContactsInfo']  = $businessPartnerContactsDetails;
		$modelOutput[0]['businessPartnerInfo'] 			= $businessPartnerDetails;
		$modelOutput[0]['empInfo']		 				= $employeeDetails;
		$modelOutput[0]['statusInfo']					= $statusDetails;

		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: frameSalesQuoteItem()
	 *
	 * @DESC 		: TO FRAME THE SALES QUOTE ITEM 
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function frameSalesQuoteItem($getSalesQuoteItemList)
	{
		// PASS CURRENCY DATA 
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 0;

		// BIND THE LIST SUB ARRAY 
		if (count($getSalesQuoteItemList) > 0) {

			foreach ($getSalesQuoteItemList as $key => $value) {

				// ITEM DETAILS 
				$passItemData['id']   	 = $value['item_id'];
				$itemDetails   	 		 = $this->commonModel->getItemAutoList(array_merge($passSearchData, $passItemData));
				$value['itemInfo'] 		 = $itemDetails;


				// TAX DETAILS
				$passTaxData['id']   = $value['tax_id'];
				$taxDetails   	   	 = $this->commonModel->getStageAutoList(array_merge($passSearchData, $passTaxData));
				$value['taxInfo'] = $taxDetails;

				// UOM DETAILS
				$passUomData['id'] 	 	= $value['uom_id'];
				$uomDetails  		 	= $this->commonModel->getUomAutoList(2, array_merge($passSearchData, $passUomData));
				$value['uomInfo'] 		= $uomDetails;

				// DISTRIBUTION DETAILS 
				$distributionRulesDetails	= array();
				$distributionRulesId  		= $value['distribution_rules_id'];
				$distributionRulesArray  	= explode(",", $distributionRulesId);

				if (count($distributionRulesArray) > 0) {
					foreach ($distributionRulesArray as $distributionKey => $distributionValue) {
						if (!empty($distributionValue)) {
							$passDistributionRulesData['id'] 		= $distributionValue;
							$getDistributionRulesData  = $this->commonModel->getdistributionRulesAutoList(array_merge($passSearchData, $passDistributionRulesData));
							if (is_array($getDistributionRulesData) && count($getDistributionRulesData) > 0) {
								$distributionRulesDetails[$distributionKey]	= $getDistributionRulesData[0];
							}
						}
					}
				}
				$value['distributionRulesInfo'] 		= $distributionRulesDetails;

				// Re-Assign Array 
				$getSalesQuoteItemList[$key] = $value;
			}
		}
		return $getSalesQuoteItemList;
	}


	/**
	 * @METHOD NAME 	: getPurchaseList()
	 *
	 * @DESC 		    : TO GET THE SALES QUOTE LIST DETAILS 
	 * @RETURN VALUE    : $outputdata array
	 * @PARAMETER 	    : -
	 * @SERVICE         : WEB
	 * @ACCESS POINT    : -
	 **/
	public function getPurchaseList()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$stgName = $this->currentRequestData['stgName'];

		$modelOutput = $this->nativeModel->getPurchaseList($this->currentRequestData, 0, $stgName);
		print_r($modelOutput);
		exit;
		foreach ($modelOutput['searchResults'] as $key => $value) {
			$getSalesQuoteItemList 		= $this->nativeModel->getPurchaseItemList($value['id'], $stgName);
			$modelOutput['searchResults'][$key]['lineItemInfo'] = $getSalesQuoteItemList;
		}

		// FRAME OUTPUT
		$outputData['results']      = $modelOutput;
		$outputData['status']       = "SUCCESS";

		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: downloadExcel()
	 *
	 * @DESC 		: TO DOWNLOAD THE EXCEL FORMAT
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function downloadExcel()
	{
		$modelOutput	= $this->nativeModel->getSalesQuoteList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('SALES_QUOTE')['excel_file_name'];

		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('SALES_QUOTE')['columns_list']);
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: getAnalyticsDetails()
	 *
	 * @DESC 		: TO GET THE ANALYTICS DETAILS FOR SALES QUOTE 
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function getAnalyticsDetails()
	{
		// CHECK THE REQUEST METHOD.
		$this->checkRequestMethod("post"); 	
		$outputData['status']  = "SUCCESS";

		// ACTIVITY STATUS DETAILS
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 1;

		//$type = $this->nativeModel->getTableNameStr();
		//$statusData['type']	= $type.'_STATUS';
		$statusData['type']	= 'SALES_QUOTE_STATUS';
		
		$StatusDetails  = $this->commonModel->getMasterStaticDataAutoList(2, array_merge($passSearchData, $statusData));

		$key = 0;
		foreach ($StatusDetails as $key => $value) {
			$totalValue = $this->nativeModel->getAnalyticsCount($value['id']);
			$analyticsData[$key]['name'] 	= $value['name'];
			$analyticsData[$key]['id'] 		= $value['id'];
			$analyticsData[$key]['count']	= $totalValue;
		}
		$key++;
		$analyticsData[$key]['name'] 	= 'All';
		$analyticsData[$key]['id'] 		= 0;
		$analyticsData[$key]['count']	= $this->nativeModel->getAnalyticsCount('');

		$outputData['results']     	= $analyticsData;
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: downloadInvoice()
	 *
	 * @DESC 		: DOWNLOAD THE SALES QUOTE INVOICE
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	: -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function downloadInvoice($id = '')
	{

		// METHOD FOR URL SHARING FEATURE 
		$outputData['status'] = "FAILURE";

		$this->load->helper('MY_download_helper');
		$companyDetails		  = $this->commonModel->getCompanyInformation();

		// SALES QUOTE DETAILS 
		$modelOutput 			= $this->nativeModel->editSalesQuote($id);
		$getSalesQuoteItemList 	= $this->nativeModel->getSalesQuoteItemList($id);

		// PASS CURRENCY DATA 
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 0;


		if (count($modelOutput) > 0) {

			$modelOutput		  = $this->frameSalesQuoteEditDetails($modelOutput);
			$data 				  = array();
			$data				  = $modelOutput[0];


			// BIND THE LIST SUB ARRAY 
			$getSalesQuoteItemList	= $this->frameSalesQuoteItem($getSalesQuoteItemList);


			if (count($companyDetails) == 1) {

				$outputData['status']   = "SUCCESS";
				$itemRowHtml 			= "";
				$totalBeforeTax			= 0;

				$companyLogo 			= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
				$salesQuoteItemCount 	= count($getSalesQuoteItemList);
				$itemRowCount	 		= 6;

				// FRAME ITEM INFORMATION
				if ($salesQuoteItemCount > 0) {

					// CALCULATE TOTAL NUMBER OF PAGES					
					$totalPages  		  = ceil($salesQuoteItemCount / $itemRowCount); // 6 ITEMS PER ROW

					$sNo 			= 1;
					$itemRowInc 	= 1;
					$totalBeforeTax	= $modelOutput[0]['total_amount'] - $modelOutput[0]['tax_percentage'];

					$pageItems = 0;
					foreach ($getSalesQuoteItemList as $salesQuoteItemDet => $salesQuoteItemValue) {
						$pageItems++;

						$rowHtml = '<tr>
									<td class="text-left">' . $sNo . '</td>
									<td class="text-left">
										<div class="semi-strong">' . $salesQuoteItemValue['itemInfo'][0]['item_name'] . '</div>
										<div><span class="ash-color">Item Code:</span>' . $salesQuoteItemValue['itemInfo'][0]['item_code'] . '</div>
										<div><span class="ash-color">Discount:</span> ' . $salesQuoteItemValue['discount_percentage'] . '%</div>
									</td>
									<td class="text-left">' . $salesQuoteItemValue['quantity'] . '</td>
									<td class="text-left">' . $salesQuoteItemValue['itemInfo'][0]['uom_name'] . '</td>
									<td class="text-left">' . $salesQuoteItemValue['unit_price'] . '</td>
									<td class="text-left">' . $salesQuoteItemValue['item_tax_percentage'] . '</td>
									<td class="text-left">' . $salesQuoteItemValue['item_tax_value'] . '</td>
									<td class="text-left">' . $salesQuoteItemValue['total_item_amount'] . '</td>
								</tr>';
						$sNo++;
						$itemRowHtml = $itemRowHtml . "" . $rowHtml;

						if ($pageItems == $itemRowCount) {
							$itemRow[$itemRowInc] = $itemRowHtml;
							$itemRowHtml = "";
							$pageItems = 0;
							$itemRowInc++;
						}
					}

					// LAST VALUE
					if (!empty($itemRowHtml)) {
						$itemRow[$itemRowInc] = $itemRowHtml;
					}
				}

				$currencyName	 = $modelOutput[0]['businessPartnerInfo'][0]['currency_name'];


				// GET BILL TO STATE INFORMATION 
				$passBillToStateData['id'] 		  = $modelOutput[0]['businessPartnerInfo'][0]['bill_to_state_id'];
				$billToStateDetails   			  = $this->commonModel->getStateAutoList(
					array_merge($passSearchData, $passBillToStateData)
				);

				// SHIP TO STATE INFO 
				$passShipToStateData['id'] 				= $modelOutput[0]['businessPartnerInfo'][0]['ship_to_state_id'];
				$shipToStateDetails   					= $this->commonModel->getStateAutoList(
					array_merge($passSearchData, $passShipToStateData)
				);


				$customerInfoHtml = '<tr>
										<td>
											<div class="ash-color">Customer No</div>
											' . $modelOutput[0]['businessPartnerInfo'][0]['partner_code'] . '
										</td>
										<td>
											<div class="ash-color">Your Reference</div>
											' . $modelOutput[0]['referrence_number'] . '
										</td>
										<td>
											<div class="ash-color">Due Date</div>
											' . $modelOutput[0]['valid_until'] . '
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<div class="ash-color">Delivery Address</div>
											' . $modelOutput[0]['businessPartnerInfo'][0]['ship_to_address'] . '<br>
											' . $shipToStateDetails[0]['country_name'] . '
											' . $shipToStateDetails[0]['state_name'] . '<br>
											' . $modelOutput[0]['businessPartnerInfo'][0]['ship_to_city'] . '
											' . $modelOutput[0]['businessPartnerInfo'][0]['ship_to_zipcode'] . '
										</td>
									</tr>';

				$billToHtml = '<tr>
								<td class="ash-color">Bill To</td>
							</tr>
								<tr>
								<td>
									' . $modelOutput[0]['businessPartnerInfo'][0]['bill_to_address'] . '<br>
									' . $billToStateDetails[0]['country_name'] . '
									' . $billToStateDetails[0]['state_name'] . '<br>
									' . $modelOutput[0]['businessPartnerInfo'][0]['bill_to_city'] . '
									' . $modelOutput[0]['businessPartnerInfo'][0]['bill_to_zipcode'] . '
									<br><br><br>
									' . $modelOutput[0]['businessPartnerInfo'][0]['tax_code'] . '
								</td>						
							</tr>';

				// SUMMARY PAGE 
				$invoiceSummaryHtml  = '<!-- Summary -->
								<div class="summary">
									<table>
										<tr>
											<td>Sub Total:</td>
											<td>' . $modelOutput[0]['total_before_discount'] . ' ' . $currencyName . '</td>
										</tr>
										<tr>
											<td>DP: ' . $modelOutput[0]['discount_percentage'] . ' %</td>
											<td>' . $modelOutput[0]['discount_value'] . ' ' . $currencyName . '</td>
										</tr>
										<tr>
											<td>Total Before Tax</td>
											<td>' . $totalBeforeTax . ' ' . $currencyName . '</td>
										</tr>
										<tr>
											<td>Tax Amount</td>
											<td>' . $modelOutput[0]['tax_percentage'] . ' ' . $currencyName . '</td>
										</tr>
										<tr class="strong">
											<td>Total Amount</td>
											<td>' . $modelOutput[0]['total_amount'] . ' ' . $currencyName . '</td>
										</tr>
									</table>
								</div>
								<!-- SIGNATURE -->
								<div class="space"></div>
								<div>
									<table>
										<tr>
											<td style="width: 50px">SIGNATURE:</td>
											<td><input type="text" class="signature" /></td>
											<td style="width: 50px">DATE:</td>
											<td><input type="text" class="signature" /></td>
										</tr>
									</table>			
								</div>
								<!-- NOTE -->
								<div class="space"></div>
								<div class="note">
									<table>
										<tr>
											<td style="width: 100px">Note:</td>
											<td>' . $modelOutput[0]['remarks'] . '</td>
										</tr>
									</table>
								</div>';

				$footerContent = '<p style="height:0px;"></p>
							  <div align="center">Thanks. We appreciate your business
							  This sales quote has been generated using <a href="www.x-factr.com">x-factr</a>. 
							  </div>';

				$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

				// PAGE MANIPULATION 
				$printingData = "";
				for ($page = 1; $page <= $totalPages; $page++) {

					$getContentData 	  	= file_get_contents(INVOICE_TEMPLATE_FILE);

					// SHOW ONLY TO LAST PAGES 
					$summaryHtmlData 		= "";
					if ($page == $totalPages) {
						$summaryHtmlData = $invoiceSummaryHtml;
						$pageBreakContent = "";
					}

					// SHOW ONLY TO FIRST PAGES
					$customerInfoDetails  = "";
					$billInfoDetails	  = "";
					if ($page == 1) {
						$customerInfoDetails  	= $customerInfoHtml;
						$billInfoDetails		= $billToHtml;
					}

					// printing data 
					$findStrings 			= array(
						"<<INVOICE_HEADING>>", "<<PAGE_NO>>",
						"<<TOTAL_PAGE_NUMBER>>",
						"<<CUSTOMER_INFO_DETAILS>>",
						"<<CURRENCY>>",
						"<<COMPANY_NAME>>", "<<COMPANY_ADDRESS>>",
						"<<COMPANY_TAX_NUMBER>>",
						"<<COMPANY_LOGO>>",
						"<<DOCUMENT_NUMBER>>", "<<DOCUMENT_DATE>>",
						"<<BILL_TO_INFO>>",
						"<<CONTENT_INFO>>",
						"<<INVOICE_SUMMARY_BLOCK>>", "<<FOOTER_CONTENT>>"
					);

					$replaceStrings  		= array(
						"SALES QUOTE", $page, $totalPages, $customerInfoDetails,
						$currencyName,
						$companyDetails[0]['company_name'],
						$companyDetails[0]['location'], $companyDetails[0]['tax_number'],
						$companyLogo,
						$modelOutput[0]['document_number'], $modelOutput[0]['document_date'],
						$billInfoDetails,
						$itemRow[$page],
						$summaryHtmlData, $footerContent
					);

					$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
					$printingData .= $pageBreakContent;
				}

				//echo $printingData;exit;

				$getHtmlFile 		= time() . '_sales_quote.html';
				$fileLocation		= INVOICE_GENERATION_PATH . $getHtmlFile;

				if (!@file_put_contents($fileLocation, $printingData)) {
					$outputData['message']   = lang('MSG_145');
				} else {
					$time = time();
					$fileName 	  = $time . "_sales_quote.pdf";
					shell_exec('wkhtmltopdf ' . $fileLocation . " " . INVOICE_GENERATION_PATH . $fileName);
					$fileDetails 	= INVOICE_GENERATION_PATH . $fileName;
					//force_download($fileName, $fileDetails);
					$invoiceFile 		= getFullImgUrl('invoice', $fileName);
					$outputData['url']  = $invoiceFile;
				}
			} else {
				$outputData['message']      = lang('MSG_143');  // COMPANY DETAILS NOT FOUND
			}
		} else {
			$outputData['message']      = lang('MSG_144'); // SALES QUOTE DETAILS NOT FOUND
		}
		$this->output->sendResponse($outputData);
	}
}
