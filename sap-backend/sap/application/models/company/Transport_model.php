<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Transport_model.php
* @Class  			 : Transport_model
* Model Name         : Transport_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 07 JUN 2024
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Transport_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr 		 = 'TRANSPORT';
		$this->itemTableNameStr		 = 'TRANSPORT_INVOICES';
		$this->tableNameStr2 		 = 'MASTER_VEHICLE';
		$this->itemTableColumnRef 	 = 'transport_id';
		$this->itemTableColumnReqRef = 'transportId';
		$this->tableName 			 =  constant($this->tableNameStr);
		$this->itemTableName 		 =  constant($this->itemTableNameStr);
		$this->tableName2 		 	 =  constant($this->tableNameStr2);
	}


	/**
	 * @METHOD NAME 	: saveTransport()
	 *
	 * @DESC 			: TO SAVE THE TRANSPORT
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT : -
	 **/
	public function saveTransport($getPostData)
	{
	
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		
		// Checks for duplicate and Process next number (both Custom, Manual).
		$DocNumInfo = processDocumentNumber($rowData, $this->tableName);
		// Assinging document number after processed.
		$rowData['document_number'] = $DocNumInfo['documentNumber'];

		// To update next document number.
		updateNextNumber($rowData, $rowData['document_numbering_type']);

		// Remove document_nubmer_type, as no need for db operation.
		unset($rowData['document_numbering_type']);
		
		$rowData['branch_id'] = $this->currentbranchId;

		$insertId 	= $this->commonModel->insertQry($this->tableName, $rowData);

		if ($insertId > 0 && isset($getPostData['invoiceListArray'])) {

			//Sub-Array Formation 
			$getListData = $getPostData['invoiceListArray'];

			foreach ($getListData as $key => $value) {
				$value[$this->itemTableColumnReqRef] = $insertId;
				$this->saveTransportInvoices($value);
			}
		}
		
		$this->updateBarCode($insertId);

		if($insertId>0){
			$this->app_db->trans_complete(); // Transaction Complete.
		}

		// Check the transaction status
		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['sId']	 = $insertId;
			$modelOutput['flag'] = 1; // Success

		}
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: saveTransportInvoices()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveTransportInvoices($getPostData)
	{

		if (!empty($getPostData[$this->itemTableColumnReqRef])) {

			$rowData = bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$value['sales_ar_invoice_id'] 	= $getPostData['salesArInvoiceId'];
			// $value['status'] = $getPostData['status'];
			$this->commonModel->insertQry($this->itemTableName, $rowData);
			$modelOutput['flag'] = 1; // Success
			
		} else {
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
	
	/**
	 * @METHOD NAME 	: updateBarCode()
	 *
	 * @DESC 		 	: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 	 	: $getPostData array
	 * @SERVICE 	 	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateBarCode($id)
	{
		$whereQry   = array('id' => $id);
		$getBarCode = generateBarCode($id);
		$rowData    = array('barcode_number'=>$getBarCode);
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}
	
	
	/**
	 * @METHOD NAME 	: updateTransport()
	 *
	 * @DESC 		 	: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 	 	: $getPostData array
	 * @SERVICE 	 	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateTransport($getPostData)
	{

		$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		$id  = $getPostData['id'];

		// Adding Transaction Start
		$this->app_db->trans_start();

		// Delete records.
		if(isset($getPostData['deletedInvoiceIds']) && count($getPostData['deletedInvoiceIds'])>0){
			foreach($getPostData['deletedInvoiceIds'] as $del_Ids){
				$this->nativeModel->deleteInvoices($del_Ids);
			}
		}
						
		if(isset($getPostData['isDraft']) && $getPostData['isDraft']==2){ // CHANGED FROM IS DRAFT 1 TO 0
			// CHECKS FOR DUPLICATE AND PROCESS NEXT NUMBER (BOTH Custom, Manual)
			$DocNumInfo = processDocumentNumber($rowData, $this->tableName);
			// Assinging document number after processed
			$rowData['document_number'] = $DocNumInfo['documentNumber'];
			// To update next document number
			updateNextNumber($rowData, $rowData['document_numbering_type']);
			
		}
		// Remove document_nubmer_type, as no need for db operation
		unset($rowData['document_numbering_type']);
		
		if(isset($getPostData['isDraft']) && $getPostData['isDraft'] == 2) {  // DRAFT MOVED TO NORMAL DOCUMENT 
			$rowData['is_draft'] = 0;
		}

		$rowData['branch_id'] = $this->currentbranchId;

		$whereQry = array('id' => $id);
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);

		if(isset($getPostData['invoiceListArray'])){
			$getListData     = $getPostData['invoiceListArray'];
			// LIST DATA. 
			foreach ($getListData as $key => $value) {
		
				$value[$this->itemTableColumnReqRef] = $id;
				$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $value);
				
				if (empty($value['id'])) { // INSERT THE RECORD 
					$this->commonModel->insertQry($this->itemTableName, $rowData);
				} else {
					$ret = $this->updateTransportInvoices($value,$id);
				}

				if($ret['flag'] == 3){ // If Invoice update flag error.
					break;
				}
		
			}

		}

		// To Complete the Transaction
		if($ret['flag'] == 1){ // check Invoice flag status.
			$this->app_db->trans_complete(); // Transaction Complete.
		}

		if ($this->app_db->trans_status() === FALSE || $ret['flag'] == 2 ) {
			$modelOutput['flag'] = 2; // Failure
		} if ($ret['flag'] == 3) {
			$modelOutput['flag'] = 3; // Invoice full validation failure.
		} else {
			$modelOutput['flag'] = 1; // Success
		}

		return $modelOutput;
	}


	/**
	 * @METHOD NAME   : updateTransportInvoices()
	 *
	 * @DESC 		  : - 
	 * @RETURN VALUE  : $modelOutput array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function updateTransportInvoices($getPostData,$transportId)
	{
		$updateFlag = 1;
				
		$modelOutput['flag'] = 0;
		// If status Fully-Transported
		if(isset($getPostData['status']) && $getPostData['status'] == 1){ 
			$transportInvoiceDetails = $this->getTransportedDetailsForInvoice($getPostData['salesArInvoiceId'],1);
			if(count($transportInvoiceDetails)>0) { // Check full status exist validation.
				$updateFlag = 0;
			}
		}
		
		
		// UPDATE THE FLAG 
		if($updateFlag === 1){
			$whereQry = array('id' => $getPostData['id']);
			$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->itemTableName, $rowData, $whereQry);
			$modelOutput['flag'] = 1; // Success
		}else {
			$modelOutput['flag'] = 3; // Invoice Validation Failure.
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: getTransportedDetailsForInvoice()
	*
	* @DESC 		: -
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getTransportedDetailsForInvoice($id,$checkCancelStatus=0)
    {
		$this->app_db->select(
								array(
										SALES_AR_INVOICE.'.id',
										SALES_AR_INVOICE.'.document_number',
										'msd.name as invoice_status_name',
										TRANSPORT_INVOICES.'.status',
										TRANSPORT_INVOICES.'.transport_id',
										TRANSPORT.'.document_number as transport_document_number',
										TRANSPORT.'.status as transport_document_status',
										'msd1.name as transportDocumentStatusName'
										//TRANSPORT.'.status as transportDocumentStatusName'
										)
							);
		$this->app_db->from(SALES_AR_INVOICE);
		$this->app_db->join(TRANSPORT_INVOICES, TRANSPORT_INVOICES.'.sales_ar_invoice_id ='.SALES_AR_INVOICE.'.id', '');
		$this->app_db->join(TRANSPORT, TRANSPORT.'.id = '.TRANSPORT_INVOICES.'.transport_id', '');
		$this->app_db->join(
							MASTER_STATIC_DATA." msd", 
							'msd.master_id = '.TRANSPORT_INVOICES.'.status AND msd.type = "TRANSPORT_INVOICE_STATUS"', ''
							);
		$this->app_db->join(
							MASTER_STATIC_DATA." msd1", 
							'msd1.master_id = '.TRANSPORT.'.status AND msd1.type = "TRANSPORT_STATUS"', ''
							);
		$this->app_db->where(SALES_AR_INVOICE.'.id',$id);
		//$this->app_db->where('a.is_deleted',0);
		//$this->app_db->where(TRANSPORT.'.status != ',3);
		if($checkCancelStatus===1){
			$this->app_db->where(TRANSPORT_INVOICES.'.status',1);
			$this->app_db->where(TRANSPORT.'.status != ',3);
		}
		$rs = $this->app_db->get();
		$resultData =  $rs->result_array();	
		return $resultData;
	}


	/**
	 * @METHOD NAME   : editTransport()
	 *
	 * @DESC 		  : -
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function editTransport($id)
	{

		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $id);

		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('id', $id);
		$this->app_db->where('is_deleted', '0');
		$this->app_db->where_in('branch_id', explode(",",$this->currentUserBranchIds));
		$rs = $this->app_db->get();
		return  $rs->result_array();
	}

		/**
	 * @METHOD NAME 	: deleteInvoices()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function deleteInvoices($id)
	{
		$whereQry  = array('id' => $id);
		$this->commonModel->deleteQry($this->itemTableName, $whereQry);

		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}

	/**
	 * @METHOD NAME 	: editInvoicesList()
	 *
	 * @DESC 			: TO GET THE SALES ORDER ITEM LIST 
	 * @RETURN VALUE 	: $rs array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editInvoicesList($id)
	{
		$rowData = bindConfigTableValues($this->itemTableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($this->itemTableName);
		$this->app_db->where($this->itemTableColumnRef, $id);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return $rs->result_array();
	}

	
	/**
	* @METHOD NAME 	: getAnalyticsCount()
	*
	* @DESC 		: TO ANALYTICS FOR VEHICLE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAnalyticsCount($statusId)
    {
		
		$this->app_db->select('COUNT(*) as total');
		$this->app_db->from(TRANSPORT);	
		
			if(!empty($statusId)){
				$this->app_db->where('status', $statusId);
			}
			
		$this->app_db->where('is_deleted',0);
		$rs = $this->app_db->get();
		$resultData =  $rs->result_array();	
		return $total = $resultData[0]['total'];
    }


	

	
	/**
	 * @METHOD NAME 	: getTransportList()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getTransportList($getPostData, $downloadFlag = '')
	{

		$query = 'SELECT * FROM 
					(SELECT
					  a.id,
					  a.document_number,
					  a.document_numbering_id,
					  a.manual_invoice_number,
					  a.tax_value,
					  a.invoice_type,					  
					  a.barcode_number,
					  a.reference_number,
					  a.posting_date,
					  a.delivery_date,
					  a.document_date,
					  a.remarks,
					  a.status,
					  a.udf_fields,
					  a.vehicle_id,
					  a.price,
					  a.total_kms,
					  a.tax_id,
					  a.tax_percentage,
					  a.total_amount,
					  a.branch_id,
					  a.created_on,
					  a.updated_on,
					  a.posting_status,
					  a.sap_id,
					  a.sap_error,
					  a.created_by,
					  CONCAT(cep.first_name," ",cep.last_name) as created_by_name,

					/* MASTER STATIC DATA (TRANSPORT) */
					msd.name as transport_status,

					/* BRANCH INFORMATION */
					mb.branch_code,
					mb.branch_name
					
					FROM ' . $this->tableName . ' as a	
					
					LEFT JOIN '.EMPLOYEE_PROFILE.' as cep 
						ON cep.id = a.created_by

					LEFT JOIN '.MASTER_STATIC_DATA.' as msd
						ON msd.master_id = a.status AND msd.type = "TRANSPORT_STATUS"
					
					LEFT JOIN '.MASTER_BRANCHES.' as mb 
						ON mb.id = a.branch_id
					
					WHERE a.is_deleted = 0
					) as a 
					WHERE id!=0';

		// TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
		$tableProperties = $getPostData['tableProperties'];
		$filters         = $getPostData['search'];

		// SEARCH
		if (count($filters) > 0) {
			foreach ($filters as $key => $value) {
				$fieldName  = $key;
				$fieldValue = $value;
				if ($fieldValue!="") {
					if ($fieldName == "documentNumber") {
						$query .= ' AND document_number = "' . $fieldValue . '"';
					} 
					else if ($fieldName == "status") {
						$query .= ' AND status = "' . $fieldValue . '"';
					} 
					else if ($fieldName == "vechicleId") {
						$query .= ' AND status = "' . $fieldValue . '"';
					} 
					else if ($fieldName == "postingDate") {
						$query .= ' AND DATE(posting_date) = "' . $fieldValue . '"';
					} 
					else if ($fieldName == "deliveryDate") {
						$query .= ' AND DATE(delivery_date) = "' . $fieldValue . '"';
					}
					else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					}
					else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					}
					else if($fieldName=="branchName"){
						$query.=' AND LCASE(CONCAT(branch_code," ",branch_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="createdByName"){
						$query.=' AND LCASE(created_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
				}
			}
		}

		// ORDERING 
		if (isset($tableProperties['sortField'])) {
			$fieldName = $tableProperties['sortField'];
			$sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('TRANSPORT')['columns_list'], $fieldName);

			if (!empty($fieldName)) {
				$query .= ' ORDER BY ' . $fieldName . ' ' . $sortOrder;
			}
		} else {
			$query .= ' ORDER BY updated_on desc';
		}

		// PAGINATION
		if (isset($tableProperties['first'])) {
			$offset = $tableProperties['first'];
			$limit  = $tableProperties['rows'];
		} else {
			$offset = 0;
			$limit  = $tableProperties['rows'];
		}

		// SEARCH RESULT DATA 
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();

		// CHECK HIRARACHY MODE
		if (($this->hierarchyMode == 1) && ($this->currentAccessControlId != 1)) { // TO FIND THE DISTRIBUTION RULES RECORD
			$searchResultData = processDistributionRulesData($searchResultData, $empDistributionRulesId);
		}
		$totalRecords = count($searchResultData);



		// DOWNLOAD BASED OPERATIONS 
		if (empty($downloadFlag)) {
			$searchResultSet = getOffSetRecords($searchResultData, $offset, $limit);
		} else {
			$searchResultSet = $searchResultData;
		}
		
		// FRAME OTHER DETAIL INFORMATION 
		$passType['type'] 	= 'TRANSPORT_STATUS';
		$StatusList 		= $this->commonModel->getMasterStaticDataAutoList($passType, 2);

		foreach ($searchResultSet as $key => $value) {
			$StatusId 	= array_search($value['status'], array_column($StatusList, 'id'));
			$statusName = "";
			if ($StatusId !== false) {
				$statusName = $StatusList[$StatusId]['name'];
			}
			$searchResultSet[$key]['status_name'] 	= $statusName;
			//$searchResultSet[$key]['emp_img_url'] 	= getFullImgUrl('employee', $value['profile_img']);
		}
		
		
		// MODEL DATA 
		$modelData['searchResults'] = $searchResultSet;
		$modelData['totalRecords']  = $totalRecords;
		return $modelData;
	}


	/**
	 * @METHOD NAME 	: getTransportReports()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getTransportReports($getPostData, $downloadFlag = '')
	{

		$query = 'SELECT * FROM 
					(SELECT

					/* TRANSPORT */
					a.id,
					a.posting_date,
					a.price,
					a.total_kms,
					a.total_amount,
					a.tax_percentage,
					a.tax_value,
					a.document_number,
					a.reference_number,
					a.remarks,

					 /* MASTER VEHICLE */
					mv.id as vehicle_id,
					mv.vehicle_code,
					mv.description,
					mv.vendor_bp_id,

					/* TRANSPORT INVOICE */
					GROUP_CONCAT(tpi.sales_ar_invoice_id) as invoice_id,
					GROUP_CONCAT(sariv.document_number) as invoice_document_name,
					tpi.status,

					/* BUSINESS PARTNER */
					bp.partner_name as vendor_bp_name,
					bp.partner_code	as vendor_bp_code,

					/* BRANCH INFORMATION */
					mb.branch_code,
					mb.branch_name,

					/* MASTER STATIC DATA (TRANSPORT) */
					msd1.name as transport_status,

					/* MASTER STATIC DATA (TRANSPORT INVOICE) */
					GROUP_CONCAT(msd2.name) as transport_invoice_status
					
					FROM ' . $this->tableName . ' as a	
					LEFT JOIN '.$this->tableName2.' as mv 
					ON mv.id = a.vehicle_id  AND mv.is_deleted = 0 

					LEFT JOIN '.$this->itemTableName.' as tpi 
					ON tpi.transport_id = a.id AND tpi.is_deleted = 0

					LEFT JOIN '.MASTER_BRANCHES.' as mb 
						ON mb.id = a.branch_id

					LEFT JOIN ' . BUSINESS_PARTNER . ' as bp
						ON bp.id = mv.vendor_bp_id

					LEFT JOIN '.MASTER_STATIC_DATA.' as msd1 
						ON msd1.master_id = a.status AND msd1.type = "TRANSPORT_STATUS"

					LEFT JOIN '.MASTER_STATIC_DATA.' as msd2 
						ON msd2.master_id = tpi.status AND msd2.type = "TRANSPORT_INVOICE_STATUS" 

					LEFT JOIN '.SALES_AR_INVOICE.' as sariv 
						ON sariv.id = tpi.sales_ar_invoice_id 
					
					WHERE a.is_deleted = 0
					AND a.status != 3
						AND a.branch_id in  (' . $this->currentUserBranchIds . ') GROUP BY a.id
					) as a 
					WHERE id!=0';

		// TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
		$tableProperties = $getPostData['tableProperties'];
		$filters         = $getPostData['search'];

		// SEARCH
		if (count($filters) > 0) {
			foreach ($filters as $key => $value) {
				$fieldName  = $key;
				$fieldValue = $value;
				if ($fieldValue!="") {
					if ($fieldName == "fromDate") {
						$query .= ' AND DATE(posting_date) >= "' . $fieldValue . '"';
					} 
					else if ($fieldName == "toDate") {
						$query .= ' AND DATE(posting_date) <= "' . $fieldValue . '"';
					} 
					else if ($fieldName == "vendorBpId") {
						$query .= ' AND vendor_bp_id = "' . $fieldValue . '"';
					} 
					else if ($fieldName == "documentNumber") {
						$query .= ' AND document_number = "' . $fieldValue . '"';
					}
					else if ($fieldName == "status") {
						$query .= ' AND status = "' . $fieldValue . '"';
					}
				}
			}
		}

		// ORDERING 
		if (isset($tableProperties['sortField'])) {
			$fieldName = $tableProperties['sortField'];
			$sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('SALES_ORDER')['columns_list'], $fieldName);

			if (!empty($fieldName)) {
				$query .= ' ORDER BY ' . $fieldName . ' ' . $sortOrder;
			}
			$query .= ' ORDER BY updated_on desc';
		}

		// PAGINATION
		if (isset($tableProperties['first'])) {
			$offset = $tableProperties['first'];
			$limit  = $tableProperties['rows'];
		} else {
			$offset = 0;
			$limit  = $tableProperties['rows'];
		}

		// SEARCH RESULT DATA 
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();
		$totalRecords = count($searchResultData);

		// DOWNLOAD BASED OPERATIONS 
		if (empty($downloadFlag)) {
			$searchResultSet = getOffSetRecords($searchResultData, $offset, $limit);
		} else {
			$searchResultSet = $searchResultData;
		}

		// MODEL DATA 
		$modelData['searchResults'] = $searchResultSet;
		$modelData['totalRecords']  = $totalRecords;
		return $modelData;
	}



	/**
	 * @METHOD NAME 	: getGatePassDetails()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getGatePassDetails($transportId)
	{

		$query = 'SELECT * FROM 
					(SELECT

					/* TRANSPORT */
					a.id,
					a.posting_date,
					a.price,
					a.total_kms,
					a.total_amount,
					a.document_number,
					a.document_date,
					a.remarks,
					a.reference_number,
					a.delivery_date,
					a.manual_invoice_number,
					a.invoice_type,

					 /* MASTER VEHICLE */
					mv.id as vehicle_id,
					mv.vehicle_code,
					mv.description,
					mv.vendor_bp_id,

					/* TRANSPORT INVOICE */
					GROUP_CONCAT(tpi.sales_ar_invoice_id) as invoice_id,
					GROUP_CONCAT(IFNULL(sariv.document_number, \'-\')) as invoices,
					tpi.status,

					/* BUSINESS PARTNER */
					bp.partner_name as vendor_bp_name,
					bp.partner_code	as vendor_bp_code,

					/* BRANCH INFORMATION */
					mb.branch_code,
					mb.branch_name,

					/* MASTER STATIC DATA (TRANSPORT) */
					msd1.name as transport_status,

					/* MASTER STATIC DATA (TRANSPORT INVOICE) */
					GROUP_CONCAT(msd2.name) as transport_invoice_status
					
					FROM ' . $this->tableName . ' as a	
					LEFT JOIN '.$this->tableName2.' as mv 
					ON mv.id = a.vehicle_id  AND mv.is_deleted = 0 

					LEFT JOIN '.$this->itemTableName.' as tpi 
					ON tpi.transport_id = a.id AND tpi.is_deleted = 0

					LEFT JOIN '.MASTER_BRANCHES.' as mb 
						ON mb.id = a.branch_id

					LEFT JOIN ' . BUSINESS_PARTNER . ' as bp
						ON bp.id = mv.vendor_bp_id

					LEFT JOIN '.MASTER_STATIC_DATA.' as msd1 
						ON msd1.master_id = a.status AND msd1.type = "TRANSPORT_STATUS"

					LEFT JOIN '.MASTER_STATIC_DATA.' as msd2 
						ON msd2.master_id = tpi.status AND msd2.type = "TRANSPORT_INVOICE_STATUS" 

					LEFT JOIN '.SALES_AR_INVOICE.' as sariv 
						ON sariv.id = tpi.sales_ar_invoice_id 
					
					WHERE a.is_deleted = 0
						AND a.id = '.$transportId.' AND a.branch_id in  (' . $this->currentUserBranchIds . ')
					) as a 
					WHERE id!=0';

		// SEARCH RESULT DATA 
		$rs	= $this->app_db->query($query);
		return $rs->result_array();

	}


	/**
	* @METHOD NAME 	: checkGatepassHistoryRecord()
	*
	* @DESC 		: CHECK GATEPASS HISTORY RECORD STATUS.
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function checkGatepassHistoryRecord($transportId)
    {
		$this->app_db->select('a.id,a.status');
		$this->app_db->from(GATEPASS_HISTORY. " a");
		$this->app_db->where('a.transport_id',$transportId);
		$this->app_db->where('a.is_deleted',0);

		$rs = $this->app_db->get();
		$resultData =  $rs->result_array();	
		return $resultData;
    }
	
	
	/**
	 * @METHOD NAME 	: getInvoiceListForTransport()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getInvoiceListForTransport()
	{
		$query = 'SELECT
					  `tbl_sales_ar_invoice`.`id`,
					  `tbl_sales_ar_invoice`.`document_number`
					FROM tbl_sales_ar_invoice
					WHERE `tbl_sales_ar_invoice`.`is_deleted` = 0
						AND (`tbl_sales_ar_invoice`.`approval_status` = 2
							  OR `tbl_sales_ar_invoice`.`approval_status` = 4)
						AND `tbl_sales_ar_invoice`.`gatepass_status` = 2
						and `tbl_sales_ar_invoice`.`is_draft` = 0
						and tbl_sales_ar_invoice.id NOT IN(
						SELECT
							 tbl_transport_invoices.sales_ar_invoice_id
						   FROM tbl_transport_invoices
						   WHERE tbl_transport_invoices.is_deleted = 0
							   AND tbl_transport_invoices.status = 1
					and tbl_transport_invoices.transport_id not in (select tbl_transport.id from tbl_transport where is_deleted=0 and status =3))';
		$rs		= $this->app_db->query($query);
		return $rs->result_array();		
	}

}
