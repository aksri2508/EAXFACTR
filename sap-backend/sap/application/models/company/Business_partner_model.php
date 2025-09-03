<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Business_partner_model.php
* @Class  			 : Business_partner_model
* Model Name         : Business_partner_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 22 JUNE 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Business_partner_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr		= 'BUSINESS_PARTNER';
		$this->subTableNameStr1 = 'BP_CONTACTS';
		$this->subTableNameStr2 = 'BP_ADDRESS';
		$this->tableName 		= constant($this->tableNameStr);
		$this->subTableName1 	= constant($this->subTableNameStr1);
		$this->subTableName2 	= constant($this->subTableNameStr2);
		$this->itemTableColumnRef	 = 'business_partner_id';
		$this->itemTableColumnReqRef = 'businessPartnerId';
    }
	
	
	/**
	* @METHOD NAME 	: saveBusinessPartner()
	*
	* @DESC 		: TO SAVE THE BUSINESS PARTNER DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function saveBusinessPartner($getPostData)
	{
		
		$subArrayList = array('contactListArray','addressListArray');

		// BIND THE ROW DATA 
		$rowData 				 = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData, $subArrayList);

		// Adding Transaction Start
		$this->app_db->trans_start();

		// Checks for duplicate and Process next number (both Custom, Manual).
		$DocNumInfo = processDocumentNumber($rowData, $this->tableName);
		// Assinging document number after processed.
		$rowData['document_number'] = $DocNumInfo['documentNumber'];
		$rowData['partner_code']	= $DocNumInfo['documentNumber'];


		// To update next document number.
		updateNextNumber($rowData, $rowData['document_numbering_type']);

		// Remove document_nubmer_type, as no need for db operation.
		unset($rowData['document_numbering_type']);
		
		// Sub-Array Formation 
		$getListData         = $getPostData['contactListArray'];
		$getAddressListData  = $getPostData['addressListArray'];

			// Adding Transaction Start
			// $this->app_db->trans_start();

			$insertId 		= $this->commonModel->insertQry($this->tableName, $rowData);

			if ($insertId > 0) {

				// CONTACT INFORMATION DETAILS
				foreach ($getListData as $key => $value) {
					$value['businessPartnerId'] = $insertId;
					$this->saveBusinessPartnerContacts($value);
				}

				// CONTACT ADDRESS DETAILS IN BUISNESS PARTNER 
				foreach ($getAddressListData as $addressKey => $addressValue) {
					$addressValue['businessPartnerId'] = $insertId;
					$this->saveBusinessPartnerAddress($addressValue);
				}
				$this->app_db->trans_complete(); // TRANSACTION COMPLETE
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
	* @METHOD NAME 	: saveBusinessPartnerContacts()
	*
	* @DESC 		: TO SAVE THE BUSINESS PARTNER CONTACTS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveBusinessPartnerContacts($getPostData)
    {
		if(!empty($getPostData[$this->itemTableColumnReqRef])){
			
			$rowData 			= bindConfigTableValues($this->subTableNameStr1, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$insertId = $this->commonModel->insertQry($this->subTableName1, $rowData);
			
			$modelOutput['flag'] = 1; // Success
		}else{
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: saveBusinessPartnerAddress()
	*
	* @DESC 		: TO SAVE THE BUSINESS PARTNER ADDRESS  
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveBusinessPartnerAddress($getPostData)
    {
		if(!empty($getPostData[$this->itemTableColumnReqRef])){			
			$rowData = bindConfigTableValues($this->subTableNameStr2, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$insertId = $this->commonModel->insertQry($this->subTableName2, $rowData);
			
			$modelOutput['flag'] = 1; // Success
		}else{
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: updateBusinessPartner()
	*
	* @DESC 		: TO UPDATE THE BUSINESS PARTNER
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateBusinessPartner($getPostData)
    {
		$deletedChildIds = $getPostData['deletedContactChildIds'];
		$delChildIdArray = $deletedChildIds;
        $id        		 = $getPostData['id'];
		$getListData     = $getPostData['contactListArray'];
		$getAddressListData  = $getPostData['addressListArray'];
		
		$deletedListArrayChildIds = $getPostData['deletedAddressChildIds'];
		
		// Adding Transaction Start
		$this->app_db->trans_start();
		
		/*
		// CHECK THE BUSINESS PARTNER
		$whereExistsQry = array(
								 'id!='				 	 => $id,
								);	
		$totRows 		= $this->commonModel->isExists($this->tableName,$whereExistsQry);
		*/
		
		// if(0 == $totRows) {
			
			$whereQry = array('id'=>$id);
			$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
           
		   
			// DELETE OPERATION
			if (count($delChildIdArray) > 0) { // Child values
				foreach ($delChildIdArray as $key => $value) {
					$whereQry  = array('id' => $value);
					$this->commonModel->deleteQry(BP_CONTACTS, $whereQry);
				}
			}

			// LIST DATA FOR CONTACTS 
			foreach ($getListData as $key => $value) {
				$value['businessPartnerId'] = $id;	
				if (empty($value['id'])) { // INSERT THE RECORD 
					$this->saveBusinessPartnerContacts($value);
				} else {
					$value['id'] = $value['id'];
					$this->updateBusinessPartnerContacts($value);
				}
			}
			
			/////////////////////// ADDRESS INFORMATION DETAILS  ///////////////////////////////////////
			// DELETE THE ADDRESS DETAILS 
			if (count($deletedListArrayChildIds) > 0) { // Child values
				foreach ($deletedListArrayChildIds as $key => $value) {
					$whereQry  = array('id' => $value);
					$this->commonModel->deleteQry(BP_ADDRESS, $whereQry);
				}
			}
			
			// LIST DATA FOR ADDRESS INFORMATION 
			foreach ($getAddressListData as $addressKey => $addressValue) {
				$addressValue['businessPartnerId'] = $id;
				if (empty($addressValue['id'])) { // INSERT THE RECORD 					
						$this->saveBusinessPartnerAddress($addressValue);
				} else {
					$addressValue['id']	 = $addressValue['id'];
					$this->updateBusinessPartnerAddress($addressValue);
				}
			}
			
			// To Complete the Transaction
			$this->app_db->trans_complete();

			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['flag'] = 1; // Success
			}
        /*
		}else { // BUSINESS PARTNER NUMBER ALREADY EXISTS 
			$modelOutput['flag'] = 4; // Data Already Exists		
		}
		*/
        return $modelOutput;
    }
    
	
	/**
	* @METHOD NAME 	: updateBusinessPartnerContacts()
	*
	* @DESC 		: TO UPDATE THE BUSINESS PARTNER CONTACTS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateBusinessPartnerContacts($getPostData)
    {
		$whereQry = array('id' => $getPostData['id']);
		$rowData  = bindConfigTableValues($this->subTableNameStr1, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->subTableName1, $rowData, $whereQry);
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: updateBusinessPartnerAddress()
	*
	* @DESC 		: TO UPDATE THE BUSINESS PARTNER ADDRESS  
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateBusinessPartnerAddress($getPostData)
    {
		$whereQry = array('id' => $getPostData['id']);
		$rowData = bindConfigTableValues($this->subTableNameStr2, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->subTableName2, $rowData, $whereQry);
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: deleteBusinessPartnerContacts()
	*
	* @DESC 		: TO DELETE THE BUSINESS PARTNER CONTACTS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function deleteBusinessPartnerContacts($getPostData)
    {
		// DELETE IN BUSINESS PARTNER CONTACTS 
		$whereQry  = array('id' => $getPostData['id']);			
		$this->commonModel->deleteQry(BP_CONTACTS,$whereQry);
		$modelOutput['flag'] = 1; // Success
        return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: deleteBusinessPartnerAddress()
	*
	* @DESC 		: TO DELETE THE BUSINESS PARTNER ADDRESS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function deleteBusinessPartnerAddress($getPostData)
    {
		// DELETE IN BUSINESS PARTNER CONTACTS 
		$whereQry  = array('id' => $getPostData['id']);			
		$this->commonModel->deleteQry(BP_ADDRESS,$whereQry);
		$modelOutput['flag'] = 1; // Success
        return $modelOutput;
    }
	
	
    /**
	* @METHOD NAME 	: editBusinessPartner()
	*
	* @DESC 		: TO EDIT THE BUSINESS PARTNER
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editBusinessPartner($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return  $rs->result_array();
    }
	
	
	/**
	* @METHOD NAME 	: getBusinessPartnerContactsList()
	*
	* @DESC 		: TO GET THE BUSINESS PARTNER STAGES LIST 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getBusinessPartnerContactsList($getPostData){
		$this->app_db->select(array('id','contact_type_id','contact_name','primary_country_code','primary_contact_no','contact_email_id','sap_id'));
        $this->app_db->from(BP_CONTACTS);
        $this->app_db->where('business_partner_id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
		$resultSet =  $rs->result_array();

		foreach($resultSet as $key => $value){
			// FRAME ALL THE INFO DATA
			$statusInfoDetails	= array();
			$getInfoData 		= array(	
										'getBusinessPartnerContactTypeList' => $value['contact_type_id'],
									);
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			$resultSet[$key]['contactTypeInfo'] =  $statusInfoDetails['contactTypeInfo'];
		}	
        return $resultSet;
	}
	
	
	/**
	* @METHOD NAME 	: getBusinessPartnerAddressList()
	*
	* @DESC 		: TO GET THE BUSINESS PARTNER ADDRESS LIST 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getBusinessPartnerAddressList($getPostData){
		$this->app_db->select(array('id','address_type_id','address','state_id','city','zipcode','default_address','tax_code'));
        $this->app_db->from(BP_ADDRESS);
        $this->app_db->where('business_partner_id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
		$resultSet =  $rs->result_array();
		
		foreach($resultSet as $key => $value){
			$statusInfoDetails	= array();
			$getInfoData 		= array(	
									'getStateList' 						=> $value['state_id'],
									'getBusinessPartnerAddressTypeList' => $value['address_type_id'],
								);
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
			$resultSet[$key]['addressTypeInfo'] = $statusInfoDetails['partnerAddressInfo'];
			$resultSet[$key]['stateInfo']		= $statusInfoDetails['stateInfo'];
		}
        return $resultSet;
	}
	
	
    /**
	* @METHOD NAME 	: getBusinessPartnerList()
	*
	* @DESC 		: TO GET THE BUSINESS PARTNER LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getBusinessPartnerList($getPostData,$downloadFlag='')
    {
		$query = 'SELECT * FROM (SELECT
									a.id,
									a.partner_type_id,
									a.partner_code,
									a.partner_name,
									a.currency_id,
									a.pan_number,
									a.status,
									a.credit_limit,
									a.account_balance,
									a.emp_id,
									a.payment_terms_id,
									a.payment_method_id,
									a.industry_id,
									a.territory_id,
									a.created_on,
									a.created_by,
									a.updated_on,
									a.updated_by,
									a.sap_id,
									a.posting_status,
									a.sap_error,
									CONCAT(b.first_name," ",b.last_name) as created_by_name,
									CONCAT(c.first_name," ",c.last_name) as updated_by_name,
									
								   /* BUSINESS PARTNER CONTACTS  */ 
									bpc.contact_name,
									bpc.contact_email_id,
									CONCAT(bpc.primary_country_code," ",bpc.primary_contact_no) AS contact_number,
									
									/* CURRENCY */
									mc.currency_name,
									
									/* INDUSTRY DETAILS */
									mi.industry_name,
									
									/* TERRITORY DETAILS */
									mt.territory_name,
									
									/* EMPLOYEE TABLE */
									CONCAT(d.first_name," ",d.last_name) as employee_name,
									
									/* PAYMENT TERMS NAME */
									mpt.payment_term_name,
									
									/* PAYMENT METHODS NAME */
									mpm.payment_method_name,
									
									/* MASTER PRICE LIST */
									mpl.price_list_name
									
								  FROM '.BUSINESS_PARTNER.' as a
									LEFT JOIN '.EMPLOYEE_PROFILE.' as b
									  ON b.id = a.created_by
									LEFT JOIN '.EMPLOYEE_PROFILE.' as c
									  ON c.id = a.updated_by
									LEFT JOIN '.EMPLOYEE_PROFILE.' as d
									  ON d.id = a.emp_id  
									LEFT JOIN (SELECT * FROM '.BP_CONTACTS.' WHERE contact_type_id = 1) as bpc
									  ON bpc.business_partner_id = a.id
									LEFT JOIN '.MASTER_CURRENCY.' as mc
									  ON mc.id = a.currency_id
									LEFT JOIN '.MASTER_INDUSTRY.' as mi
									  ON mi.id = a.industry_id
									LEFT JOIN '.MASTER_TERRITORY.' as mt
									  ON mt.id = a.territory_id 
									LEFT JOIN '.MASTER_PAYMENT_TERMS.' as mpt
									  ON mpt.id = a.payment_terms_id 
									LEFT JOIN '.MASTER_PAYMENT_METHODS.' as mpm
									  ON mpm.id = a.payment_method_id    
									LEFT JOIN '.MASTER_PRICE_LIST.' as mpl
									  ON mpl.id = a.price_list_id    
								  WHERE a.is_deleted = 0 AND 
								  bpc.is_deleted = 0 
								  ) as a
							WHERE id != 0';
		
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                 if ($fieldValue!="") {
					if($fieldName=="partnerTypeId") {
						$query.=' AND partner_type_id = "'.$fieldValue.'"';						
					}else if($fieldName=="partnerName"){						
						$query.=' AND LCASE(CONCAT(partner_code," ",partner_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
						
					}else if($fieldName=="contactName"){
						$query.=' AND LCASE(contact_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}else if($fieldName=="contactEmailId"){
						$query.=' AND LCASE(contact_email_id) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}else if($fieldName=="contactNumber"){
						$query.=' AND LCASE(contact_number) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}					
					else if($fieldName=="status"){
						$query.=' AND status = "'.$fieldValue.'"';
					}
					else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					}
					else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					} 
					else if($fieldName=="accountBalance"){
						$query.=' AND LCASE(account_balance) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="creditLimit"){
						$query.=' AND LCASE(credit_limit) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="createdOn"){
						$query.=' AND DATE(created_on) = "'.$fieldValue.'"';
					}
					else if($fieldName=="createdByName"){
						$query.=' AND LCASE(created_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="updatedByName"){
						$query.=' AND LCASE(updated_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="priceListName"){
						$query.=' AND LCASE(price_list_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('BUSINESS_PARTNER')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$query.= ' ORDER BY '.$fieldName.' '.$sortOrder;
			}
			
        }else{
			$query.= ' ORDER BY updated_on desc';
		}
        
         // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
		$rs = $this->app_db->query($query);
		$totalRecords =$rs->num_rows();
        
        // PAGINATION
		if(empty($downloadFlag)){	
			if (isset($tableProperties['first'])) {
				$offset = $tableProperties['first'];
				$limit  = $tableProperties['rows'];
			} else {
				$offset = 0;
				$limit  = $tableProperties['rows'];
			}
			$query.=' LIMIT '.$offset.','.$limit;
        }
		
        // GET RESULTS 		
        $searchResultSet = $this->app_db->query($query);
        $searchResultSet = $searchResultSet->result_array();
		
		
		// BUSINESS PARTNER TYPE
		$passType['type'] 	  		= 'BUSINESS_PARTNER_TYPE';
		$businessPartnerTypeList  	= $this->commonModel->getMasterStaticDataAutoList($passType,2);
		
		// BUSINESS PARTNER STATUS
		$passType['type'] 	  		= 'COMMON_STATUS';
		$businessPartnerStatusList  = $this->commonModel->getMasterStaticDataAutoList($passType,2);
		
		foreach($searchResultSet as $key => $value){
			$businessPartnerStatusId 	= array_search($value['status'], array_column($businessPartnerStatusList, 'id'));
			$statusName = "";
			if($businessPartnerStatusId !== false){
				$statusName = $businessPartnerStatusList[$businessPartnerStatusId]['name'];
			}
			
			$businessPartnerTypeId 	= array_search($value['partner_type_id'], array_column($businessPartnerTypeList, 'id'));
			$typeName = "";
			if($businessPartnerTypeId !== false){
				$typeName = $businessPartnerTypeList[$businessPartnerTypeId]['name'];
			}
				
			// SEARCH RESULTS DATA 			
			$searchResultSet[$key]['status_name'] 			= $statusName;
			$searchResultSet[$key]['type_name'] 			= $typeName;
		}
		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
	
	/**
	* @METHOD NAME 	: getAnalyticsCount()
	*
	* @DESC 		: TO ANALYTICS FOR ACTIVITY
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAnalyticsCount($statusId)
    {
		$this->app_db->select('COUNT(*) as total');
		$this->app_db->from(BUSINESS_PARTNER);	
		
			if(!empty($statusId)){
				$this->app_db->where('status',$statusId);
			}
			
		$this->app_db->where('is_deleted',0);
		$rs = $this->app_db->get();
		$resultData =  $rs->result_array();	
		return $total = $resultData[0]['total'];
    }

}
