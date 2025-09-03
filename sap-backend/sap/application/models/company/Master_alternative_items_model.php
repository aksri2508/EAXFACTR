<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_alternative_items_model.php
* @Class  			 : Master_alternative_items_model
* Model Name         : Master_reason_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_alternative_items_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr 		 = 'MASTER_ALTERNATIVE_ITEMS';
		$this->itemTableNameStr 	 = 'MASTER_ALTERNATIVE_ITEMS_LIST';
		$this->itemTableColumnRef 	 = 'master_alternative_item_id';
		$this->itemTableColumnReqRef = 'masterAlternativeItemId';
		$this->tableName 			 = constant($this->tableNameStr);
		$this->itemTableName		 = constant($this->itemTableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveAlternativeItems()
	*
	* @DESC 		: TO SAVE THE ALTERNATIVE ITEM
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveAlternativeItems($getPostData)
    {
		// BIND THE ROW DATA 
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		
		// Sub-Array Formation 
		$getListData         = $getPostData['altItemListArray']; 
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'item_id' => strtolower($getPostData['itemId']),
							);
		$chkMasterRecord = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		
        if (0 == $chkMasterRecord) {

			// Adding Transaction Start
			$this->app_db->trans_start();
		
			$insertId = $this->commonModel->insertQry($this->tableName, $rowData);

			if ($insertId > 0) {
			  foreach ($getListData as $key => $value) {
					$value[$this->itemTableColumnReqRef] = $insertId;
					$this->saveAlternativeItemList($value);
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
		}else{
			$modelOutput['flag'] = 4; // Data Already Exists
			return $modelOutput;
		}
	}
	
    
	/**
	* @METHOD NAME 	: saveAlternativeItemList()
	*
	* @DESC 		: TO SAVE THE ALTERNATIVE ITEM LIST 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveAlternativeItemList($getPostData)
    {
		if(!empty($getPostData[$this->itemTableColumnReqRef])){
			$rowData = bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$insertId = $this->commonModel->insertQry($this->itemTableName, $rowData);
			$modelOutput['flag'] = 1; // Success
		}else{
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: updateAlternativeItems()
	*
	* @DESC 		: TO UPDATE THE ALTERNATIVE ITEMS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateAlternativeItems($getPostData)
    {
		$delChildIdArray = $getPostData['deletedAltItemChildIds'];
        $id        		 = $getPostData['id'];
		$getListData     = $getPostData['altItemListArray'];

		// Adding Transaction Start
		$this->app_db->trans_start();
		
		// CHECK THE BUSINESS PARTNER
		$whereExistsQry = array(
								 'item_id' => strtolower($getPostData['itemId']),
								 'id!='	   => $id,
								);	
		$totRows 		= $this->commonModel->isExists($this->tableName,$whereExistsQry);

        if(0 == $totRows) {
			
			// UPDATE IN MAIN TABLE 
			$whereQry = array('id'=>$id);
			$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
           
		   
			// DELETE OPERATION.
			if (count($delChildIdArray) > 0) { // Child values
				foreach ($delChildIdArray as $key => $value) {
					$passId  = array('id' => $value);
					$this->deleteAlternativeItemList($passId);
				}
			}
			
			// SAVE OR UPDATE OPERATION PERFORM
			foreach ($getListData as $key => $value) {
				if (empty($value['id'])) { // INSERT THE RECORD 
					$value[$this->itemTableColumnReqRef] = $id;
					$this->saveAlternativeItemList($value);
				} else {
					$this->updateAlternativeItemList($value);
				}
			}
			
			// To Complete the Transaction
			$this->app_db->trans_complete();

			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['flag'] = 1; // Success
			}
        }else { // BUSINESS PARTNER NUMBER ALREADY EXISTS 
			$modelOutput['flag'] = 4; // Data Already Exists		
		}
        return $modelOutput;
    }
    
	
	/**
	* @METHOD NAME 	: updateAlternativeItemList()
	*
	* @DESC 		: TO UPDATE THE ALTERNATIVE ITEM LIST 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateAlternativeItemList($getPostData)
    {
		$whereQry = array('id' => $getPostData['id']);
		$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->itemTableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1;
		return $modelOutput;
	}
	
	
	/**
	 * @METHOD NAME 	: deleteAlternativeItemList()
	 *
	 * @DESC 			: TO DELETE THE ALTERNATIVE ITEM LIST 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function deleteAlternativeItemList($getPostData)
	{
		$whereQry  = array('id' => $getPostData['id']);
		$this->commonModel->deleteQry($this->itemTableName, $whereQry);
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}
	
	
    /**
	* @METHOD NAME 	: editAlternativeItems()
	*
	* @DESC 		: TO EDIT THE ALTERNATIVE ITEMS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editAlternativeItems($getPostData)
    {
		$id = $getPostData['id'];
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('id', $id);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return  $rs->result_array();
    }
    
   
    /**
	 * @METHOD NAME   : editAlternativeItemsList()
	 *
	 * @DESC 		  : TO EDIT THE ALTERNATIVE ITEM LIST
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function editAlternativeItemsList($id)
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
	* @METHOD NAME 	: getAlternativeItemsList()
	*
	* @DESC 		: TO GET THE ALTERNATIVE ITEM LIST INFORMATION
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAlternativeItemsList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_ALTERNATIVE_ITEMS.'.id',
								MASTER_ALTERNATIVE_ITEMS.'.item_id',
								MASTER_ALTERNATIVE_ITEMS.'.status',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.alt_item_id',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.match_factor',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.remarks',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.posting_status',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.sap_id',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName',
								'MI.item_name as item_name',
								'MI.item_code as item_code',
								MASTER_ITEM.'.item_name as alternative_item_name',
								MASTER_ITEM.'.item_code as alternative_item_code',
							));
        $this->app_db->from(MASTER_ALTERNATIVE_ITEMS);
		$this->app_db->join(MASTER_ALTERNATIVE_ITEMS_LIST, MASTER_ALTERNATIVE_ITEMS_LIST.'.master_alternative_item_id = '.MASTER_ALTERNATIVE_ITEMS.'.id', '');
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_ALTERNATIVE_ITEMS.'.status', '');
		$this->app_db->join(MASTER_ITEM.' MI' ,'MI.id = '.MASTER_ALTERNATIVE_ITEMS.'.item_id', '');
		$this->app_db->join(MASTER_ITEM, MASTER_ITEM.'.id = '.MASTER_ALTERNATIVE_ITEMS_LIST.'.alt_item_id', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_ALTERNATIVE_ITEMS.'.is_deleted', '0');
		$this->app_db->where(MASTER_ALTERNATIVE_ITEMS_LIST.'.is_deleted', '0');


        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
				
				if ($fieldValue!="") {
					if($fieldName=="remarks") {
						$this->app_db->like('LCASE('.MASTER_ALTERNATIVE_ITEMS_LIST.'.remarks)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_ALTERNATIVE_ITEMS.'.status', $fieldValue);
					}else if($fieldName=="matchFactor"){
						$this->app_db->like('match_factor', $fieldValue);
					}else if($fieldName=="itemName") {
						$this->app_db->like('LCASE(concat(MI.item_code,MI.item_name))',strtolower($fieldValue));
					}else if($fieldName=="alternativeItemName") {
						$this->app_db->like('LCASE(concat('.MASTER_ITEM.'.item_name,'.MASTER_ITEM.'.item_code))',strtolower($fieldValue));
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_ITEM.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_ITEM.'.posting_status', $fieldValue);
					}
				}
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_ALTERNATIVE_ITEMS')['columns_list'],$fieldName);
			
			//echo "FIELD NAME IS ::".$fieldName;
			
			if(!empty($fieldName)){
				//echo "Order by condition ";
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_ALTERNATIVE_ITEMS.'.updated_on', 'desc');
		}
        
        // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
        $tempdb       = clone $this->app_db;
        $totalRecords = $tempdb->count_all_results();
        
        // PAGINATION
        if (isset($tableProperties['first'])) {
            $offset = $tableProperties['first'];
            $limit  = $tableProperties['rows'];
        } else {
            $offset = 0;
            $limit  = $tableProperties['rows'];
        }
        $this->app_db->limit($limit, $offset);
        
        // GET RESULTS 		
        $searchResultSet = $this->app_db->get();
        $searchResultSet = $searchResultSet->result_array();
        
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
}
?>