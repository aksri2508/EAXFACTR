<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_item_group_model.php
* @Class  			 : Master_item_group_model
* Model Name         : Master_item_group_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 11 MAY 2018
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_item_group_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_ITEM_GROUP';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveItemGroup()
	*
	* @DESC 		: TO SAVE THE ITEM GROUP
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveItemGroup($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(group_name)' => strtolower($getPostData['groupName']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_ITEM_GROUP,$whereExistsQry);
		
        if (0 == $chkRecord) {
			$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$insertId = $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateItemGroup()
	*
	* @DESC 		: TO UPDATE THE ITEM GROUP
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateItemGroup($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(group_name)' => strtolower($getPostData['groupName']),
								 'id!='				 => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_ITEM_GROUP,$whereExistsQry);
		               
        if(0 == $totRows) {		
			$whereQry = array('id'=>$getPostData['id']);			
			$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editItemGroup()
	*
	* @DESC 		: TO EDIT THE ITEM GROUP
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editItemGroup($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
       // $this->app_db->select(array('id','group_code','group_name','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_ITEM_GROUP);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getItemGroupList()
	*
	* @DESC 		: TO GET THE ITEM GROUP LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getItemGroupList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_ITEM_GROUP.'.id',
								MASTER_ITEM_GROUP.'.group_code',
								MASTER_ITEM_GROUP.'.group_name',
								MASTER_ITEM_GROUP.'.status',
								MASTER_ITEM_GROUP.'.posting_status',
								MASTER_ITEM_GROUP.'.sap_id',
								MASTER_ITEM_GROUP.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName'
							));
							
        $this->app_db->from(MASTER_ITEM_GROUP);
		
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_ITEM_GROUP.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_ITEM_GROUP.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="groupCode") {
						$this->app_db->like('LCASE(group_code)', strtolower($fieldValue));
					}else if($fieldName=="groupName"){
						$this->app_db->like('LCASE(group_name)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_ITEM_GROUP.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_ITEM_GROUP.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_ITEM_GROUP.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_ITEM_GROUP')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_ITEM_GROUP.'.updated_on', 'desc');
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
