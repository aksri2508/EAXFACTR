<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_price_list_model.php
* @Class  			 : Master_price_list_model
* Model Name         : Master_price_list_model
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
class Master_price_list_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_PRICE_LIST';
		$this->tableName 	= constant($this->tableNameStr);
		$this->chkPrimaryId = 0;
    }
	
	
	/**
	* @METHOD NAME 	: checkMappingIdExists()
	*
	* @DESC 		: TO CHECK MAPPING ID EXISTS WHILE SAVING / UPDATE OPERATION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function checkMappingIdExists($getPostData,$flag){
		
		$defaultPriceListId 		= $getPostData['defaultPriceListId'];
		$passData['id'] 			= $defaultPriceListId;
		$getDefaultPriceListDetails = $this->editMasterPriceList($passData);
	
		if(count($getDefaultPriceListDetails) > 0){
			$getDbDefaultPriceListId 	= $getDefaultPriceListDetails[0]['default_price_list_id'];
			if($getDbDefaultPriceListId == $defaultPriceListId){
					return true;
			}else {
				if(($flag==2) && ($this->chkPrimaryId == $getDefaultPriceListDetails[0]['default_price_list_id'])){
					return false;
				}
				$getDefaultPriceListDetails[0]['defaultPriceListId'] = $getDefaultPriceListDetails[0]['default_price_list_id']; 
				return $this->checkMappingIdExists($getDefaultPriceListDetails[0],$flag);
			}
		}else{
			return false;
		}
	}
	
	
	/**
	* @METHOD NAME 	: saveMasterPriceList()
	*
	* @DESC 		: TO SAVE THE MASTER PRICE LIST 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveMasterPriceList($getPostData)
    {	
		$rowData				  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		$chkFlag 				  = true;
 		
		if(!empty($getPostData['defaultPriceListId'])){
			$chkFlag = $this->checkMappingIdExists($getPostData,1);
		}

		if($chkFlag){
					
			$this->app_db->trans_start();
			
			// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
			$whereExistsQry = array(
								  'LCASE(price_list_name)' => strtolower($getPostData['priceListName']),
								);
			$chkRecord = $this->commonModel->isExists(MASTER_PRICE_LIST,$whereExistsQry);
		
			if (0 == $chkRecord) {
				$insertId = $this->commonModel->insertQry($this->tableName, $rowData);
				
				if(empty($getPostData['defaultPriceListId'])){
					$getPostData['id'] = $insertId;
					$getPostData['defaultPriceListId'] = $insertId;
					
					// DIRECTLY UPDATE THE ID IN TABLE 
					$whereQry 	= array('id'=>$getPostData['id']);
					$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
					$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
				
				}
			} else {
				$modelOutput['flag'] = 2;
				return $modelOutput;
			}
			$this->app_db->trans_complete();
			
			// Check the transaction status
			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['flag'] = 1; // Success
			}
			
		}else{
			$modelOutput['flag'] = 3;
		}
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateMasterPriceList()
	*
	* @DESC 		: TO UPDATE THE PRICE LIST
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateMasterPriceList($getPostData)
    {
		$this->chkPrimaryId = $getPostData['id'];
		
		if($this->checkMappingIdExists($getPostData,2)){
			$whereExistsQry = array(
									 'LCASE(price_list_name)' => strtolower($getPostData['priceListName']),
									 'id!='				 	  => $getPostData['id'],
									);	
			
			$totRows = $this->commonModel->isExists(MASTER_PRICE_LIST,$whereExistsQry);
						   
			if(0 == $totRows) {
				$whereQry 	= array('id'=>$getPostData['id']);
				$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
				$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
				$modelOutput['flag'] = 1;
			} else {
				$modelOutput['flag'] = 2;
			}
		}else {
			$modelOutput['flag'] = 3;
		}
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editMasterPriceList()
	*
	* @DESC 		: TO EDIT MASTER PRICE REASON
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editMasterPriceList($getPostData)
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
	* @METHOD NAME 	: getMasterPriceList()
	*
	* @DESC 		: TO GET THE MASTER PRICE LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getMasterPriceList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_PRICE_LIST.'.id',
								MASTER_PRICE_LIST.'.price_list_name',
								MASTER_PRICE_LIST.'.default_price_list_id',
								MASTER_PRICE_LIST.'.default_factor',
								MASTER_PRICE_LIST.'.is_system_config',
								MASTER_PRICE_LIST.'.posting_status',
								MASTER_PRICE_LIST.'.sap_id',
								MASTER_PRICE_LIST.'.sap_error',
								'MPL.price_list_name as default_price_list_name'
							));
							
        $this->app_db->from(MASTER_PRICE_LIST);
		$this->app_db->join(MASTER_PRICE_LIST.' MPL' ,'MPL.id = '.MASTER_PRICE_LIST.'.default_price_list_id', 'left');
        $this->app_db->where(MASTER_PRICE_LIST.'.is_deleted', '0');
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
				if ($fieldValue!="") {
					if($fieldName=="priceListName") {
						$this->app_db->like('LCASE('.MASTER_PRICE_LIST.'.price_list_name)', strtolower($fieldValue));
					}else if($fieldName=="defaultFactor"){
						$this->app_db->like('LCASE('.MASTER_PRICE_LIST.'.default_factor)', strtolower($fieldValue));
					}else if($fieldName=="defaultPriceListName"){
						$this->app_db->like('LCASE(MPL.price_list_name)', strtolower($fieldValue));
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_PRICE_LIST.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_PRICE_LIST.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_PRICE_LIST')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				if($fieldName=='LCASE(price_list_name)'){
					$fieldName = MASTER_PRICE_LIST.'.price_list_name';
				}else if($fieldName=='LCASE(default_price_list_name)'){
					$fieldName = 'MPL.price_list_name';
				}
				$this->app_db->order_by($fieldName, $sortOrder);
			}
        }else{
			$this->app_db->order_by(MASTER_PRICE_LIST.'.updated_on', 'desc');
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