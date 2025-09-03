<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_item_model.php
* @Class  			 : Master_item_model
* Model Name         : Master_item_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 22 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_item_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr 			= 'MASTER_ITEM';
		$this->itemTableNameStr		 	= 'ITEM_WAREHOUSE';
		$this->masterItemPriceListStr 	= 'MASTER_ITEM_PRICE_LIST';
		$this->itemTableColumnRef 		= 'item_id';
		$this->itemTableColumnReqRef 	= 'itemId';
		$this->tableName 				= constant($this->tableNameStr);
		$this->itemTableName 			= constant($this->itemTableNameStr);
		$this->masterItemPriceListTableName	= constant($this->masterItemPriceListStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveItem()
	*
	* @DESC 		: TO SAVE THE ITEM DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function saveItem($getPostData)
	{

		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

		// Checks for duplicate and Process next number (both Custom, Manual).
		$DocNumInfo = processDocumentNUmber($rowData, $this->tableName);

		// Assigning documentNumberType for further use.
		$documentNumberType = $rowData['document_numbering_type'];


		if ($DocNumInfo['Status'] == 'FAIL') {
			$modelOutput['flag'] = $DocNumInfo['StatusNumber']; // Failure
			return $modelOutput;
		} else {
			$rowData['document_number'] = $DocNumInfo['documentNumber'];
			unset($rowData['document_numbering_type']);
		}

		// Adding Transaction Start
		$this->app_db->trans_start();

		$whereExistsQry = array(
			'LCASE(item_code)' => strtolower($getPostData['itemCode']),
		);
		$chkRecord = $this->commonModel->isExists(MASTER_ITEM, $whereExistsQry);

		if (0 == $chkRecord) {

	// If type is not Manual, Update next number.
		if ($documentNumberType != 'MANUAL') {
			// Increment and Update Document Numbmer.
			$numberStatus = updateNextNumber($rowData, $this->tableName);

			if (isset($numberStatus) && $numberStatus['Status'] == 'FAIL') {
				$modelOutput['flag'] = $numberStatus['StatusNumber']; // Failure
				return $modelOutput;
			}
		}

			$rowData['last_price_list_id'] = $getPostData['priceListId'];
			$insertId 					   = $this->commonModel->insertQry($this->tableName, $rowData);

			// SAVE TO ITEM PRICE LIST 
			$getPostData['itemId'] 	= $insertId;
			$priceListOutput 		= $this->saveItemPriceList($getPostData);

			if ($insertId > 0) {
				// Sub-Array Formation 
				$getWarehouseListData         = $getPostData['warehouseListArray'];
				// INSERT THE WAREHOUSE DETAILS
				foreach ($getWarehouseListData as $key => $value) {
					$value[$this->itemTableColumnReqRef] = $insertId;
					$this->saveItemWarehouse($value);
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
		} else {
			$modelOutput['flag'] = 2;
		}
		return $modelOutput;
	}
    
	
	/**
	* @METHOD NAME 	: saveItemWarehouse()
	*
	* @DESC 		: TO SAVE THE ITEM IN THE WAREHOUSE DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveItemWarehouse($getPostData)
    {
		if (!empty($getPostData[$this->itemTableColumnReqRef])) {
			$rowData 							= bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$insertId 							= $this->commonModel->insertQry($this->itemTableName, $rowData);
			$modelOutput['flag'] = 1; // Success
		}else{
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: saveItemPriceList()
	*
	* @DESC 		: TO SAVE THE ITEM PRICE LIST 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveItemPriceList($getPostData)
    {
		if (!empty($getPostData[$this->itemTableColumnReqRef])) {
			$rowData 							= bindConfigTableValues($this->masterItemPriceListStr, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$insertId 							= $this->commonModel->insertQry($this->masterItemPriceListTableName, $rowData);
			$modelOutput['flag'] = 1; // Success
		}else{
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: updateItem()
	*
	* @DESC 		: TO UPDATE THE ITEM
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateItem($getPostData)
    {	
		// Sub-Array Formation 
		$getWarehouseListData         = $getPostData['warehouseListArray'];
		$deletedWarehouseChildIds 	  = $getPostData['deletedWarehouseChildIds'];
		$id        					  = $getPostData['id'];
		
		$whereExistsQry = array(
								 'LCASE(item_code)' => strtolower($getPostData['itemCode']),
								'id!='				  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_ITEM,$whereExistsQry);
		  
        if(0 == $totRows) {
			
			// ADDING TRANSACTION START
			$this->app_db->trans_start();
			
			// UPDATE IN MASTER ITEM PRICE LIST
			$this->updateItemPriceList($getPostData);
			
			
			$whereQry 	 = array('id'=>$getPostData['id']);	
			$rowData	 = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$rowData['last_price_list_id'] = $getPostData['priceListId'];
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		
			// DELETE OPERATION
			if (count($deletedWarehouseChildIds) > 0) { // Child values
				foreach ($deletedWarehouseChildIds as $key => $value) {
					$whereQry  = array('id' => $value);
					$this->commonModel->deleteQry(ITEM_WAREHOUSE, $whereQry);
				}
			}
			
			// LIST DATA FOR UPDATE CONDITON 
			foreach ($getWarehouseListData as $key => $value) {
				$value[$this->itemTableColumnReqRef] = $id;		
				if (empty($value['id'])) { // INSERT THE RECORD 
					$this->saveItemWarehouse($value);	
				}else {
					$value['id']	 = $value['id'];
					$this->updateItemWarehouse($value);
				}
			}
			
			$this->app_db->trans_complete(); // TRANSACTION COMPLETE
			
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 3;
        }
        return $modelOutput;
    }
    
	
	/**
	* @METHOD NAME 	: updateItemWarehouse()
	*
	* @DESC 		: TO UPDATE THE ITEM WAREHOUSE DETAILS   
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateItemWarehouse($getPostData)
    {
		$whereQry = array('id' => $getPostData['id']);
		$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->itemTableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: updateItemPriceList()
	*
	* @DESC 		: TO UPDATE THE ITEM PRICE LIST 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateItemPriceList($getPostData)
    {
		$whereExistsQry = array(
								 'price_list_id' => strtolower($getPostData['priceListId']),
								 'item_id'		 => $getPostData['id'],
								);
								
		$totRows		= $this->commonModel->isExists(MASTER_ITEM_PRICE_LIST,$whereExistsQry);
	
		if(0 == $totRows) { // SAVE OPERATION
			$getPostData['itemId'] 	= $getPostData['id'];
			$priceListOutput		= $this->saveItemPriceList($getPostData);
		}else if(1 == $totRows){ // UPDATE OPERATION
			$whereQry = array(	
								'item_id' 		=> $getPostData['id'],
								'price_list_id' => strtolower($getPostData['priceListId'])
							);
			$rowData = bindConfigTableValues($this->masterItemPriceListStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->masterItemPriceListTableName, $rowData, $whereQry);
			$modelOutput['flag'] = 1; // Success
			return $modelOutput;
		}
	}
	
	
	/**
	* @METHOD NAME 	: deleteItemWarehouse()
	*
	* @DESC 		: TO DELETE THE ITEM WAREHOUSE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function deleteItemWarehouse($getPostData)
    {
		// DELETE IN ITEM WAREHOUSE 
		$whereQry  = array('id' => $getPostData['id']);			
		$this->commonModel->deleteQry(ITEM_WAREHOUSE,$whereQry);
	
		$modelOutput['flag'] = 1; // Success
        return $modelOutput;
    }
	
	
    /**
	* @METHOD NAME 	: editItem()
	*
	* @DESC 		: TO EDIT THE ITEM
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editItem($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData); 
        $this->app_db->from(MASTER_ITEM);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
	
	/**
	* @METHOD NAME 	: getUnitPriceByLastPriceListId()
	*
	* @DESC 		: SERVICE USED ONLY FOR EDIT PURPOSE
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getUnitPriceByLastPriceListId($getPostData)
    {
		$rowData = array('price_list_id','unit_price');
		$this->app_db->select($rowData); 
        $this->app_db->from(MASTER_ITEM_PRICE_LIST);
        $this->app_db->where('item_id', $getPostData['itemId']);
        $this->app_db->where('price_list_id', $getPostData['lastPriceListId']);
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
	
	
	/**
	* @METHOD NAME 	: getWarehouseList()
	*
	* @DESC 		: TO GET THE WAREHOUSE LIST 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getWarehouseList($getPostData){
		$this->app_db->select(array('id','item_id','warehouse_id','bin_id','status'));
        $this->app_db->from(ITEM_WAREHOUSE);
        $this->app_db->where('item_id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
		$resultSet =  $rs->result_array();
		

		foreach($resultSet as $key => $value){
			
			// FRAME ALL THE INFO DATA
			$statusInfoDetails	= array();
			
			// GET BUSINESS PARTNER CONTACTS LIST 
			$getInfoData		 = array(
										'getWarehouseList' 	 => $value['warehouse_id'],
										'getBinList' 	 	 => $value['bin_id'],
									);
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
			//printr($statusInfoDetails);exit;
			if(!empty($statusInfoDetails['warehouseInfo'])){
				$resultSet[$key]['binInfo'] = $statusInfoDetails['binInfo'];
				$resultSet[$key]['warehouseInfo'] = $statusInfoDetails['warehouseInfo'];
			}else{
				unset($resultSet[$key]);
			}
		}
		//printr($resultSet);exit;
		$resultSet = array_values($resultSet);
        return $resultSet;
	}
	
   
    /**
	* @METHOD NAME 	: getItemList()
	*
	* @DESC 		: TO GET THE ITEM LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getItemList($getPostData,$downloadFlag='')
    {
        // SELECT 
		$query =	'SELECT * FROM
						(
							SELECT
							  a.id,
							  a.item_code,
							  a.item_name,
							  a.item_image,
							  a.item_group_id,
							  a.uom_id,
							  a.status,
							  a.stock,
							  a.updated_on,
							  a.sap_id,
							  a.posting_status,
							  a.sap_error,
							  a.created_by,
					  		  CONCAT(cep.first_name," ",cep.last_name) as created_by_name,
					  
							  
							  /* Master ITEM GROUP */
							  mig.group_code,
							  mig.group_name,
							  
							  /* Master STATIC DATA */
							   msd.name as status_name,
							   
							  /* Master UOM DATA */
							  muom.uom_name as uom_name,

							  /* Master ITEM PRICE LIST */
							  mipl.unit_price as unit_price
	
							FROM '.MASTER_ITEM.' as a 
							  JOIN '.MASTER_ITEM_GROUP.' as mig
								ON mig.id = a.item_group_id
							  JOIN '.MASTER_STATIC_DATA.' as msd
								ON msd.master_id = a.status
							 LEFT JOIN '.MASTER_UOM.' as muom
								ON muom.id = a.uom_id 	
							
							 LEFT JOIN '.EMPLOYEE_PROFILE.' as cep 
								ON cep.id = a.created_by
							
							 LEFT JOIN '.MASTER_ITEM_PRICE_LIST.' as mipl 
								ON mipl.id = a.last_price_list_id
							
							WHERE msd.type = "COMMON_STATUS"
								AND a.is_deleted = 0
						)
					as a WHERE id!=0';
	
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="itemName") {
						$query.=' AND LCASE(CONCAT(item_code," ",item_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					} 
					else if($fieldName=="itemCode"){
						$query.=' AND LCASE(item_code) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					} 
					else if($fieldName=="itemGroupId"){
						$query.=' AND item_group_id ='.$fieldValue;
					} 
					else if($fieldName=="stock"){
						$query.=' AND LCASE(stock) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					} 
					else if($fieldName=="status"){
						if ($fieldValue!=""){
							$query.=' AND status ="'.$fieldValue.'"';	
						}
					}
					else if ($fieldName == "sapId") {
						$query.= ' AND a.sap_id ="'.$fieldValue.'"';
					}
					else if ($fieldName == "postingStatus") {
						$query.= ' AND a.posting_status = "'.$fieldValue.'"';
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
			$fieldName	   = getListingParams($this->config->item('MASTER_ITEM')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$query.= ' ORDER BY '.$fieldName.' '.$sortOrder;
			}			
	
        }else{
			$query.= ' ORDER BY updated_on desc';
		}
		
	
        
		// CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
		$rs =  $this->app_db->query($query);
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
		
		// FRAME PHOTO URL 
		foreach($searchResultSet as $key => $value){
			$value['itemImgUrl'] 	= getFullImgUrl('itemphoto',$value['item_image']);
			$searchResultSet[$key] 	= $value;
		}
		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
}
